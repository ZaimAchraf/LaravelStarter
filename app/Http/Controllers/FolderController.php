<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UploadController;
use App\Http\Controllers\Helper\ValidationHelper;
use App\Models\Client;
use App\Models\Credit;
use App\Models\Employee;
use App\Models\Folder;
use App\Models\FolderDocument;
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

    public function foldersType(String $type)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $folders = Folder::where('type', $type)->get();
//        return $type;
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

        $steps = [
            'Documents véhicule' => $folder->documents->where('type', 'DV')->count() > 0,
            'Documents assurance' => $folder->documents->where('type', 'DA')->count() > 0,
            'Images avant' => $folder->documents->where('type', 'IMG_AV')->count() > 0,
            'Images en cours' => $folder->documents->where('type', 'IMG_EC')->count() > 0,
            'Images après' => $folder->documents->where('type', 'IMG_AP')->count() > 0,
            'Devis confirmés' => $folder->quotations->where('type', 'Accordé')->count() > 0,
            'Facture' => $folder->invoices->count() > 0 // Assumer qu'il y a une relation invoices avec le folder
        ];

//        return $steps;

        $totalSteps = count($steps);
        $completedSteps = count(array_filter($steps));
        $progress = ($completedSteps / $totalSteps) * 100;

        return view('backOffice.folders.show', compact('folder', 'steps', 'progress'));
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

            $request->validate([
                'type_folder' => 'required|string|max:255'
            ], [
                'label.required' => 'Le champ type dossier est requis.'
            ]);

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
                'type' => $request->input('type_folder')
            ]);

            $folder->save();

            DB::commit();
//            return $folder;
            // Redirection vers une page de confirmation ou de récapitulatif
            return redirect()->route('folders.show', $folder);

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

    public function destroy(Folder $folder)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();

        try {

            if ($folder->quotations()->count() == 0) {
                $folder->documents()->delete();
                $folder->delete();

            } else {
                return redirect()->back()->withErrors(['error' => 'Pas possible de supprimer les dossiers contenant des devis']);
            }

            DB::commit();


//            return "success";
            return redirect()->back()->with('info', 'Dossier supprimé avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

//            return $e->getMessage();

            return redirect()->back()->withErrors(['error' => 'Erreur lors du traitement: ' . $e->getMessage()]);
        }

    }


    public function updatePurchases(Request $request)
    {

//        return $request;

        DB::beginTransaction();

        try {

            foreach ($request->input('lines') as $index => $lineData) {
                $quotationLine = QuotationLine::find($lineData['ql']);

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


                $quotationLine->save();
            }

//            DB::rollBack();
            DB::commit();

            return redirect()->route('folders.show', $request->input('folder'));

        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error', 'Erreur lors du traitement: ' . $e->getMessage()]);
        }
    }

    public function uploadFiles(Request $request, Folder $folder)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'type' => 'required|string|max:255'
        ], [
            'label.required' => 'Le champ type document est requis.'
        ]);

        $type = $request->input('type');

        if ($request->hasFile('documents')) {
            if ($type == "DV" || $type == "DA" || $type == "DF") {
                if (count($request->file('documents')) > 1) {
                    return redirect()->back()->withErrors(['error' => 'vous ne pouvez pas ajouter plusieurs documents à la fois pour le type selectionné.']);
                }

                $request->validate([
                    'label' => 'required|string|max:255'
                ], [
                    'label.required' => 'Le champ libellé du document est requis pour les documents de vehicule et d\'assurance.'
                ]);
            }

            foreach ($request->file('documents') as $file) {
                $doc = new FolderDocument();
                $doc->folder_id = $folder->id;
                $doc->type = $request->input('type');
                $doc->label = $request->input('label');
                $doc->name = UploadController::folderDocs($file, $folder, $doc->type);
                $doc->save();
            }

            DB::commit();

            return redirect()->route('folders.show', $folder)->with('success', 'Fichiers téléchargés avec succès!');
        } else {
            return redirect()->back()->withErrors(['error' => 'Les documents sont obligatoires.']);
        }





    }




}
