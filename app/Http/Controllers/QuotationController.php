<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Quotation;
use App\Models\QuotationLine;
use App\Models\User;
use App\Models\Vehicle;
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

        return view('backOffice.devis.index');
    }

    public function create()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.devis.create');
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

    private function validateVehicleAndQuotationLines(Request $request)
    {
        return $request->validate([
            'label' => 'required|string|max:255',
            'lines.*.description' => 'required|string|max:255',
            'lines.*.price' => 'required|numeric|min:0',
        ], [
            'label.required' => 'Le champ libellé du véhicule est requis.',
            'lines.*.description.required' => 'La description de la ligne de devis est requise.',
            'lines.*.price.required' => 'Le prix de la ligne de devis est requis.',
            'lines.*.price.numeric' => 'Le prix de la ligne de devis doit être un nombre.',
            'lines.*.price.min' => 'Le prix de la ligne de devis doit être supérieur à 0.',
        ]);
    }


    public function store(Request $request)
    {

//        DB::beginTransaction();
//
//        try {
            if ($request->has('nom_entreprise') && $request->input('nom_entreprise')) {
                if($request->has('nom_entreprise')) {
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

            } else {
                $client = Client::find($request->input('exist_client'));
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


            $this->validateVehicleAndQuotationLines($request);

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

            // Enregistrer le devis avec ses lignes
            $quotation = Quotation::create([
                'client_id' => $client->id,
                'vehicle_id' => $vehicle->id,
                // Autres champs du devis
            ]);

            // Enregistrer les lignes de devis
            foreach ($request->input('lines') as $lineData) {
                QuotationLine::create([
                    'quotation_id' => $quotation->id,
                    'description' => $lineData['description'],
                    'price' => $lineData['price'],
                    'quantity' => $lineData['quantity'],
                    'state' => $lineData['state'],
                ]);
            }

            Log::info('Données sauvegardées avec succès.');
            DB::commit();

            // Redirection vers une page de confirmation ou de récapitulatif
            return redirect()->route('devis.create', $quotation->id);

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
}
