<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Credit;
use App\Models\Employee;
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

class QuotationController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $quotations = Quotation::all();
        return view('backOffice.quotations.index', compact('quotations'));
    }



    public function create()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::all();

        return view('backOffice.quotations.create', compact('clients'));
    }

    private function validateNewClient(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ], [
            'name.required' => 'Le nom du client est requis.',
            'phone.required' => 'Le numéro Telephone est requis.',
        ]);
    }

    private function validateNewClientEntreprise(Request $request)
    {
        return $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'ICE' => 'required|string|max:255',
            'phone_contact' => 'required|string|max:255',
        ], [
            'nom_entreprise.required' => 'Le nom de l\'entreprise est requis.',
            'ICE.required' => 'Le numéro ICE est requis.',
            'phone_contact.required' => 'Le téléphone de contact est requis.',
        ]);
    }

    private function validateNewUser(Request $request)
    {
        return $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.unique' => 'Le nom d\'utilisateur est déjà utilisé.',
            'email.unique' => 'L\'adresse email est déjà utilisée.',
            'password.require' => 'le mot de passe est obligatoire pour creer un compte.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.'
        ]);
    }

    private function validateQuotationLines(Request $request)
    {
        return $request->validate([
            'lines.*.description' => 'required|string|max:255',
            'lines.*.price' => 'required|numeric|min:0',
        ], [
            'lines.*.description.required' => 'La description de la ligne de quotations est requise.',
            'lines.*.price.required' => 'Le prix de la ligne de quotations est requis.',
            'lines.*.price.numeric' => 'Le prix de la ligne de quotations doit être un nombre.',
            'lines.*.price.min' => 'Le prix de la ligne de quotations doit être supérieur à 0.',
        ]);
    }

    public function edit(Quotation $quotation)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.quotations.edit', compact('quotation'));
    }


    public function store(Request $request)
    {

//        DB::beginTransaction();
//
//        try {
            if ($request->has('new_client')) {
                if($request->has('nom_entreprise')  && $request->input('nom_entreprise')) {
                    $this->validateNewClientEntreprise($request);
                    $client = Client::Create([
                        'name' => $request->input('nom_entreprise'),
                        'ICE' => $request->input('ICE'),
                        'phone' => $request->input('phone_contact'),
                        'entreprise_yn' => '1',
                    ]);
                }else{
                    $this->validateNewClient($request);
                    $client = Client::Create([
                        'name' => $request->input('name'),
                        'phone' => $request->input('phone'),
                    ]);
                }

                if ($request->has('nouveau_compte')) {
                    $this->validateNewUser($request);
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
                        'registration' => $request->input('registration'),
                        'chassis_number' => $request->input('chassis_number')
                    ],
                    [
                        'label' => $request->input('label'),
                        'insurance' => $request->input('insurance'),
                        'client_id' => $client->id,
                    ]
                );
            }else{
                $vehicle = Vehicle::find($request->input('exist_vehicle'));
            }

            $this->validateQuotationLines($request);



            // Enregistrer le quotations avec ses lignes
            $quotation = Quotation::create([
                'client_id' => $client->id,
                'vehicle_id' => $vehicle->id,
            ]);

            $total = 0;
            foreach ($request->input('lines') as $lineData) {
                QuotationLine::create([
                    'quotation_id' => $quotation->id,
                    'description' => $lineData['description'],
                    'price' => $lineData['price'],
                    'TVA' => $lineData['TVA'] ?? 0,
                    'quantity' => $lineData['quantity'] ?? 1,
                    'state' => $lineData['state'],
                ]);

                $total += $lineData['price'] * $lineData['quantity'] * (1 +($lineData['TVA']/100));
            }

            $quotation->total = $total;
            $quotation->save();

            Log::info('Données sauvegardées avec succès.');
            DB::commit();

            // Redirection vers une page de confirmation ou de récapitulatif
            return redirect()->route('quotations.getPDF', $quotation->id);

//        } catch (\Illuminate\Validation\ValidationException $e) {
//            DB::rollback();
//            return response()->json(['errors' => $e->errors()], 422);
//        }
//        }
////        catch (\Exception $e) {
////            // En cas d'erreur, annulez la transaction
////            DB::rollBack();
////
////            // Log pour enregistrer l'erreur
////            Log::error('Erreur lors du traitement: ' . $e->getMessage());
////
////            // Gérez l'erreur ou redirigez vers une page d'erreur
////            return redirect()->back()->with('error_message', 'Erreur lors du traitement: ' . $e->getMessage());
////        }
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

        $this->validateQuotationLines($request);



        $total = 0;
        foreach ($request->input('lines') as $lineData) {

            QuotationLine::updateOrCreate([
                'id' => $lineData['id']
            ],
            [
                'quotation_id' => $quotation->id,
                'description' => $lineData['description'],
                'price' => $lineData['price'],
                'TVA' => $lineData['TVA'] ?? 0,
                'quantity' => $lineData['quantity'] ?? 1,
                'state' => $lineData['state'],
            ]);

            $total += $lineData['price'] * $lineData['quantity'] * (1 +($lineData['TVA']/100));
        }

        $quotation->total = $total;
        $quotation->credit->total = $total;
        $quotation->credit->save();
        $quotation->save();

        return redirect()->back()->with('success', 'Devis bien modifié !');
    }

    public function deleteLine( Request $request )
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ql = QuotationLine::findOrFail($request->input('line_id'));
        $ql->delete();

        return 'success';
    }
}
