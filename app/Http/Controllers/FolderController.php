<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UploadController;
use App\Http\Controllers\Helper\ValidationHelper;
use App\Models\Client;
use App\Models\Credit;
use App\Models\Employee;
use App\Models\Folder;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Quotation;
use App\Models\QuotationLine;
use App\Models\User;
use App\Models\Vehicle;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FolderController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folders = Folder::all();
        return view('backOffice.folders.index', compact('folders'));
    }

    public function create()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::all();

        return view('backOffice.folders.create', compact('clients'));
    }

    public function show(Folder $folder)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $folder;

//        return view('backOffice.folders.edit', compact('folder'));
    }

    public function edit(Folder $folder)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.folders.edit', compact('folder'));
    }


    public function store(Request $request)
    {

//        return $request;

        DB::beginTransaction();

        try {
            if ($request->has('new_client')) {

                if($request->has('nom_entreprise')  && $request->input('nom_entreprise')) {

                    ValidationHelper::validateEntrepriseClient($request, null);

                    $client = Client::Create([
                        'name' => $request->input('nom_entreprise'),
                        'ICE' => $request->input('ICE'),
                        'phone' => $request->input('phone_contact'),
                        'entreprise_yn' => '1',
                    ]);

                } else {

                    ValidationHelper::validateSimpleClient($request);

                    $client = Client::Create([
                        'name' => $request->input('name'),
                        'phone' => $request->input('phone'),
                    ]);
                }

                if ($request->has('nouveau_compte')) {

                    ValidationHelper::validateNewUser($request);

                    $user = User::create([
                        'name' => $request->input('name'),
                        'phone' => $request->input('phone'),
                        'username' => $request->input('username'),
                        'gendre' => $request->input('gendre'),
                        'role_id' => '3',
                        'email' => $request->input('email'),
                        'password' => Hash::make($request->input('password')),
                        'adresse' => $request->input('adresse'),
                    ]);

                    $client->user_id = $user->id;
                    $client->save();
                }

            } else {
                $client = Client::find($request->input('exist_client'));
            }


            if ($request->has('new_vehicle')) {

                $request->validate([
                    'label' => 'required|string|max:255'
                ], [
                    'label.required' => 'Le champ libellé du véhicule est requis.'
                ]);

                $vehicle = Vehicle::updateOrCreate(
                    [
                        'registration' => $request->input('registration')
                    ],
                    [
                        'label' => $request->input('label'),
                        'insurance' => $request->input('insurance'),
                        'client_id' => $client->id,
                        'police_number' => $request->input('police_number'),
                        'mileage' => $request->input('mileage'),
                        'chassis_number' => $request->input('chassis_number')
                    ]
                );
            }else{
                $vehicle = Vehicle::find($request->input('exist_vehicle'));
            }

            $folder = Folder::create([
                'client_id' => $client->id,
                'vehicle_id' => $vehicle->id,
                'title' => $request->input('title'),
            ]);

            $folder->save();

            DB::commit();
            return $folder;
            // Redirection vers une page de confirmation ou de récapitulatif
            return redirect()->route('folders.index');

//        } catch (\Illuminate\Validation\ValidationException $e) {
//            DB::rollback();
//            return response()->json(['errors' => $e->errors()], 422);
//        }
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

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

        return redirect()->route('quotations.index');
    }

    public function activate(Quotation $quotation)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $credit = new Credit();
        $credit->total = $quotation->total;
        $credit->quotation_id = $quotation->id;
        $credit->save();

        $quotation->is_active = 1;
        $quotation->save();


        return redirect()->route('quotations.index');
    }

    public function update(Request $request, Quotation $quotation)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $this->validateQuotationLines($request);
        DB::beginTransaction();

        try {
            $total = 0;
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
                                'lines.' . $index . 'exist_provider.required' => 'Le choix du fournisseur est obligatoire dans la ligne ' . ($index + 1) . '.',
                                'lines.' . $index . 'exist_provider.exists' => 'Le Fournisseur choisi dans la ligne ' . ($index + 1) . ' n\'existe pas dans la base de données.',
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
