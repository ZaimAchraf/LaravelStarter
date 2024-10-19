<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UploadController;
use App\Http\Controllers\Helper\ValidationHelper;
use App\Models\Credit;
use App\Models\Folder;
use App\Models\FolderDocument;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Quotation;
use App\Models\QuotationLine;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class QuotationController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $quotations = Quotation::all();
        return view('backOffice.quotations.index', compact('quotations'));
    }

    public function show(Quotation $quotation)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $this->getPDF($quotation->id);
    }

    public function create(Folder $folder)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $documentCount = FolderDocument::where('folder_id', $folder->id)
            ->where('type', 'IMG_AV')
            ->count();

        if ($documentCount == 0) {
            return redirect()->back()->withErrors(['error', 'Vous ne pouvez pas creer des devis avant d\'ajouter les photos avant reparation.']);
        }

        $products = Product::where('Qte', '>', 0)->get();
        $providers = Provider::all();

        return view('backOffice.quotations.create', compact( 'products', 'providers', 'folder'));
    }

    private function validateQuotationLines(Request $request)
    {
        return $request->validate([
            'lines.*.price' => 'required|numeric|min:0',
        ], [
            'lines.*.price.required' => 'Le prix de la ligne de quotations est requis.',
            'lines.*.price.numeric' => 'Le prix de la ligne de quotations doit être un nombre.',
            'lines.*.price.min' => 'Le prix de la ligne de quotations doit être supérieur à 0.',
        ]);
    }

    public function edit(Quotation $quotation)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::where('Qte', '>', 0)->get();
        $providers = Provider::all();
        return view('backOffice.quotations.edit', compact('quotation', 'products', 'providers'));
    }

    public function editPurhases(Folder $folder)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $providers = Provider::all();

        return view('backOffice.quotations.update_purchases', compact(  'providers', 'folder'));
    }


    public function store(Request $request)
    {

//        return $request;

        DB::beginTransaction();

        try {



            $folder = Folder::find($request->input('folder'));
//            return var_dump(empty($folder->quotations['items']));
            if ($request->input('type') == 'Initial' && !empty($folder->quotations['items'])) {
                return redirect()->back()->withErrors(['errors' => 'Vous ne pouvez pas creer plusieurs devis initials pour le meme dossier.']);
            }

            $quotation = Quotation::create([
                'folder_id' => $request->input('folder'),
                'title' => $request->input('title'),
                'type' => $request->input('type')
            ]);

            $total = 0;

            foreach ($request->input('lines') as $index => $lineData) {
                $quotationLine = new QuotationLine();


                $quotationLine->price = $lineData['price'];
                $quotationLine->TVA = $lineData['TVA'];
                $quotationLine->type = $lineData['type'];
                $quotationLine->quotation_id = $quotation->id;

                if ($lineData['type'] == 'Produit')
                {
                    $quotationLine->quantity = $lineData['quantity'] ?? 1;

                    if (isset($lineData['new_product']))
                    {
//                        return $request->input('lines');
//                        $product = Product::Create([
//                            'label' => $lineData['label'],
//                            'ref' => $lineData['ref']
//                        ]);

                        $quotationLine->description = $lineData['label'];
                        $quotationLine->state = $lineData['state'];
                        $quotationLine->reference = $lineData['ref'];

                    }else if (isset($lineData['exist_product'])){

                        $product = Product::find($lineData['exist_product']);

                        if (!$product) {
                            return redirect()->back()->withErrors(['errors' => 'Le produit séléctionné n\'a pas été trouvé.']);
                        }

                        if ($product->Qte < ($lineData['quantity'] ?? 1)) {
                            return 'error qte';
                            return redirect()->back()->withErrors(['error', 'La quantité demandée n\'est pas disponible.']);
                        }

                        $quotationLine->description = $product->label;
                        $quotationLine->state = $product->ref ? 'Nouveau' : 'Occasion';
                        $quotationLine->reference = $product->ref;

//                        $product->Qte -= ($lineData['quantity'] ?? 1);

                        $product->save();
                    }

                    if (!isset($lineData['exist_provider'])) {

                        if ($lineData['provider_name']) {
                            ValidationHelper::validateNewProviderQuotation($request, $index);
                            $provider = Provider::Create([
                                'name' => $lineData['provider_name'],
                                'phone' => $lineData['provider_phone']
                            ]);
                        }


                    } else {
                        $request->validate([
                            'lines.' . $index . '.exist_provider' => 'required|exists:providers,id',
                        ], [
                            'lines.' . $index . 'exist_provider.required' => 'Le choix du fournisseur est obligatoire dans la ligne ' . ($index + 1) . '.',
                            'lines.' . $index . 'exist_provider.exists' => 'Le Fournisseur choisi dans la ligne ' . ($index + 1) . ' n\'existe pas dans la base de données.',
                        ]);
                        $provider = Provider::find($lineData['exist_provider']);
                    }

                    if ($lineData['provider_name']) {
                        $request->validate([
                            'lines.' . $index . '.purchase_price' => 'required',
                        ], [
                            'lines.' . $index . '.purchase_price.required' => 'Le prix d\'achat est obligatoire si vous precisez le type <Produit> ainsi que le fournisseur.',
                        ]);
                    }
                    if (isset($provider)) {
                        $quotationLine->purchase_price = $lineData['purchase_price'];
                        $quotationLine->provider_id = $provider->id;
                    }
                }else {
                    $quotationLine->description = $lineData['description'];
                }

                $quotationLine->save();

                $total += $lineData['price'] * ($lineData['quantity'] ?? 1) * (1 +($lineData['TVA']/100));
            }

            $quotation->total = $total;
            $quotation->save();

//            return $quotation->quotationLines;

//            DB::rollBack();
            DB::commit();
            // Redirection vers une page de confirmation ou de récapitulatif
            return redirect()->route('folders.show', $folder->id)->with('success', 'Devis numero : ' . $quotation->id .  ' bien créé !');

//        } catch (\Illuminate\Validation\ValidationException $e) {
//            DB::rollback();
//            return response()->json(['errors' => $e->errors()], 422);
//        }
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            // Gérez l'erreur ou redirigez vers une page d'erreur
            return redirect()->back()->withErrors(['error', 'Erreur lors du traitement: ' . $e->getMessage()]);
        }
    }

    public function getPDF($idQuotation) {

        $quotation = Quotation::find($idQuotation);

//        return view('backOffice.quotations.pdf', ['quotation' => $quotation]);

        $pdf = new Dompdf();
        $pdf->loadHtml(view('backOffice.quotations.pdf', ['quotation' => $quotation]));

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $pdf->setOptions($options);

        $pdf->render();
        return  $pdf->stream(null, ['Attachment' => false]);
    }

    public function getBL($idQuotation) {

        $quotation = Quotation::find($idQuotation);

//        return view('backOffice.quotations.pdf', ['quotation' => $quotation]);

        $pdf = new Dompdf();
        $pdf->loadHtml(view('backOffice.delivery_notes.pdf', ['quotation' => $quotation]));

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $pdf->setOptions($options);

        $pdf->render();
        return  $pdf->stream(null, ['Attachment' => false]);
    }

    public function destroy(Quotation $quotation)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $quotation->quotationLines()->delete();
        $quotation->delete();

        return redirect()->back()->with('success', 'Devis bien supprimé !');
    }

    public function activate(Quotation $quotation)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $credit = new Credit();
//        $credit->total = $quotation->total;
//        $credit->quotation_id = $quotation->id;
//        $credit->save();

        $products = Product::where('Qte', '>', 0)->get();
        $providers = Provider::all();
        return view('backOffice.quotations.create_accord', compact('quotation', 'products', 'providers'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $this->validateQuotationLines($request);
        DB::beginTransaction();

        try {
            $total = 0;

            $quotation->title = $request->input('title') ?? '';

            foreach ($request->input('lines') as $index => $lineData) {

    //            QuotationLine::updateOrCreate([
    //                'id' => $lineData['id']
    //            ],
    //            [
    //                'quotation_id' => $quotation->id,
    //                'description' => $lineData['description'],
    //                'price' => $lineData['price'],
    //                'TVA' => $lineData['TVA'] ?? 0,
    //                'quantity' => $lineData['quantity'] ?? 1,
    //                'state' => $lineData['state'] == 'null' ? null : $lineData['state'],
    //            ]);

                if (isset($lineData['id'])) {
                    $ql = QuotationLine::find($lineData['id']);
                    if (!$ql) return  back()->withErrors(['error' => 'Aucune ligne de devis avec l\'id : ' . $lineData['id']]);

                    $ql->price = $lineData['price'];
                    $ql->TVA = $lineData['TVA'];

                    if ($ql->type != 'MOD') $ql->quantity = $lineData['quantity'];

                    $ql->save();
                }else {
                    $quotationLine = new QuotationLine();


                    $quotationLine->price = $lineData['price'];
                    $quotationLine->TVA = $lineData['TVA'];
                    $quotationLine->type = $lineData['type'];
                    $quotationLine->quotation_id = $quotation->id;

                    if ($lineData['type'] == 'Produit')
                    {
                        $quotationLine->quantity = $lineData['quantity'] ?? 1;

                        if (isset($lineData['new_product']))
                        {
//                        return $request->input('lines');
//                        $product = Product::Create([
//                            'label' => $lineData['label'],
//                            'ref' => $lineData['ref']
//                        ]);

                            $quotationLine->description = $lineData['label'];
                            $quotationLine->state = $lineData['state'];
                            $quotationLine->reference = $lineData['ref'];

                        }else if (isset($lineData['exist_product'])){

                            $product = Product::find($lineData['exist_product']);

                            if (!$product) {
                                return redirect()->back()->withErrors(['errors' => 'Le produit séléctionné n\'a pas été trouvé.']);
                            }

                            if ($product->Qte < ($lineData['quantity'] ?? 1)) {
                                return 'error qte';
                                return redirect()->back()->withErrors(['error', 'La quantité demandée n\'est pas disponible.']);
                            }

                            $quotationLine->description = $product->label;
                            $quotationLine->state = $product->ref ? 'Nouveau' : 'Occasion';
                            $quotationLine->reference = $product->ref;

//                            $product->Qte -= ($lineData['quantity'] ?? 1);

                            $product->save();
                        }

                        if (!isset($lineData['exist_provider'])) {

                            ValidationHelper::validateNewProviderQuotation($request, $index);

                            $provider = Provider::Create([
                                'name' => $lineData['provider_name'],
                                'phone' => $lineData['provider_phone']
                            ]);

                        } else {
                            $request->validate([
                                'lines.' . $index . '.exist_provider' => 'required|exists:providers,id',
                            ], [
                                'lines.' . $index . '.exist_provider.required' => 'Le choix du fournisseur est obligatoire dans la ligne ' . ($index + 1) . '.',
                                'lines.' . $index . '.exist_provider.exists' => 'Le Fournisseur choisi dans la ligne ' . ($index + 1) . ' n\'existe pas dans la base de données.',
                            ]);
                            $provider = Provider::find($lineData['exist_provider']);
                        }

                        $request->validate([
                            'lines.' . $index . '.purchase_price' => 'required',
                        ], [
                            'lines.' . $index . '.purchase_price.required' => 'Le prix d\'achat est obligatoire si vous precisez le type <Produit>.',
                        ]);

                        $quotationLine->purchase_price = $lineData['purchase_price'];
                        $quotationLine->provider_id = $provider->id;

                    }else {
                        $quotationLine->description = $lineData['description'];
                    }

                    $quotationLine->save();
                }

                $total += isset($lineData['quantity']) ? $lineData['price'] * $lineData['quantity'] * (1 +($lineData['TVA']/100)) : $lineData['price']  * (1 +($lineData['TVA']/100));
            }

            $quotation->total = $total;

            if ($quotation->credit) {
                $quotation->credit->total = $total;
                $quotation->credit->save();
            }

            $quotation->save();

            DB::commit();

            return redirect()->back()->with('success', 'Devis bien modifié !');
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

                // Gérez l'erreur ou redirigez vers une page d'erreur
            return redirect()->back()->withErrors(['error', 'Erreur technique lors du traitement' . $e->getMessage()]);
        }
    }



    public function createAccord(Request $request, Quotation $quotation)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $this->validateQuotationLines($request);
        DB::beginTransaction();

        try {

            if (!$request->hasFile('document')) {
                return  back()->withErrors(['error' => 'Le document de l\'accord est obligatoire.']);
            }else {
                $doc = new FolderDocument();
                $doc->folder_id = $quotation->folder->id;
                $doc->type = "DF";
                $doc->label = "Devis Accordé";
                $doc->name = UploadController::folderDocs($request->file('document'), $quotation->folder, $doc->type);
                $doc->save();
            }


            $total = 0;

            $accord = new Quotation();
            $accord->folder_id = $quotation->folder_id;
            $accord->is_active = 1;
            $accord->type = 'Accordé';


            $accord->title = $request->input('title') ?? '';

            $accord->save();

            foreach ($request->input('lines') as $index => $lineData) {

                if (isset($lineData['id'])) {

                    $exist_ql = QuotationLine::find($lineData['id']);

                    if (!$exist_ql) return  back()->withErrors(['error' => 'Aucune ligne de devis avec l\'id : ' . $lineData['id']]);

                    $ql = $exist_ql->replicate();
                    $ql->price = $lineData['price'];
                    $ql->quotation_id = $accord->id;
                    $ql->TVA = $lineData['TVA'];

                    if ($ql->type != 'MOD') $ql->quantity = $lineData['quantity'];

                    $ql->save();
                }else {
                    $quotationLine = new QuotationLine();


                    $quotationLine->price = $lineData['price'];
                    $quotationLine->TVA = $lineData['TVA'];
                    $quotationLine->type = $lineData['type'];
                    $quotationLine->quotation_id = $accord->id;

                    if ($lineData['type'] == 'Produit')
                    {
                        $quotationLine->quantity = $lineData['quantity'] ?? 1;

                        if (isset($lineData['new_product']))
                        {
//                        return $request->input('lines');
//                        $product = Product::Create([
//                            'label' => $lineData['label'],
//                            'ref' => $lineData['ref']
//                        ]);

                            $quotationLine->description = $lineData['label'];
                            $quotationLine->state = $lineData['state'];
                            $quotationLine->reference = $lineData['ref'];

                        }else if (isset($lineData['exist_product'])){

                            $product = Product::find($lineData['exist_product']);

                            if (!$product) {
                                return redirect()->back()->withErrors(['errors' => 'Le produit séléctionné n\'a pas été trouvé.']);
                            }

                            if ($product->Qte < ($lineData['quantity'] ?? 1)) {
                                return 'error qte';
                                return redirect()->back()->withErrors(['error', 'La quantité demandée n\'est pas disponible.']);
                            }

                            $quotationLine->description = $product->label;
                            $quotationLine->state = $product->ref ? 'Nouveau' : 'Occasion';
                            $quotationLine->reference = $product->ref;

//                            $product->Qte -= ($lineData['quantity'] ?? 1);

                            $product->save();
                        }

                        if (!isset($lineData['exist_provider'])) {

                            ValidationHelper::validateNewProviderQuotation($request, $index);

                            $provider = Provider::Create([
                                'name' => $lineData['provider_name'],
                                'phone' => $lineData['provider_phone']
                            ]);

                        } else {
                            $request->validate([
                                'lines.' . $index . '.exist_provider' => 'required|exists:providers,id',
                            ], [
                                'lines.' . $index . '.exist_provider.required' => 'Le choix du fournisseur est obligatoire dans la ligne ' . ($index + 1) . '.',
                                'lines.' . $index . '.exist_provider.exists' => 'Le Fournisseur choisi dans la ligne ' . ($index + 1) . ' n\'existe pas dans la base de données.',
                            ]);
                            $provider = Provider::find($lineData['exist_provider']);
                        }

                        $request->validate([
                            'lines.' . $index . '.purchase_price' => 'required',
                        ], [
                            'lines.' . $index . '.purchase_price.required' => 'Le prix d\'achat est obligatoire si vous precisez le type <Produit>.',
                        ]);

                        $quotationLine->purchase_price = $lineData['purchase_price'];
                        $quotationLine->provider_id = $provider->id;

                    }else {
                        $quotationLine->description = $lineData['description'];
                    }

                    $quotationLine->save();
                }

                $total += isset($lineData['quantity']) ? $lineData['price'] * $lineData['quantity'] * (1 +($lineData['TVA']/100)) : $lineData['price']  * (1 +($lineData['TVA']/100));
            }

            $accord->total = $total;

            $accord->save();
            $quotation->is_active = 1;
            $quotation->save();

            if ($quotation->folder->credit) {
                $quotation->folder->credit->total += $accord->total;
            }else {
                $quotation->folder->credit = new Credit();
                $quotation->folder->credit->total = $accord->total;
                $quotation->folder->credit->folder_id = $quotation->folder->id;
            }
            $quotation->folder->credit->save();

            DB::commit();

            return redirect()->route('folders.show', $folder->id)->with('success', 'L\'accord a bien été crée !');
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            // Gérez l'erreur ou redirigez vers une page d'erreur
            return redirect()->back()->withErrors(['error', 'Erreur technique lors du traitement' . $e->getMessage()]);
        }
    }

    public function deleteLine( Request $request )
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();
        try {
            $ql = QuotationLine::findOrFail($request->input('line_id'));
            $quotation = $ql->quotation;

            $quotation->total -= $ql->quantity ? $ql->price * $ql->quantity * (1 + ($ql->TVA/100)) : $ql->price  * (1 + ($ql->TVA/100));
            $quotation->save();
            $ql->delete();
            DB::commit();

            return 'success';
        } catch (\Exception $ex) {

            DB::rollBack();
            Log::error('Erreur lors du traitement: ' . $ex->getMessage());
            return 'Erreur technique lors du traitement';
        }
    }
}
