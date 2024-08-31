<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class CommonActionsHelper extends Controller
{
    public static function createClient(Request $request)
    {
        DB::beginTransaction();

        try {

            if($request->has('nom_entreprise')  && $request->input('nom_entreprise')) {

                ValidationHelper::validateEntrepriseClient($request);

                $client = Client::Create([
                    'name' => $request->input('nom_entreprise'),
                    'ICE' => $request->input('ICE'),
                    'phone' => $request->input('phone_contact'),
                    'driver_name' => $request->input('driver_name'),
                    'entreprise_yn' => '1',
                ]);

                $name = $request->input('nom_entreprise');
                $phone = $request->input('phone_contact');

            } else {

                ValidationHelper::validateSimpleClient($request, null);

                $client = Client::Create([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                ]);

                $name = $request->input('name');
                $phone = $request->input('phone');
            }

            if ($request->has('nouveau_compte')) {

                ValidationHelper::validateNewUser($request);

                $user = User::create([
                    'name' => $name,
                    'phone' => $phone,
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

            DB::commit();

            return $client;
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            // Gérez l'erreur ou redirigez vers une page d'erreur
            return redirect()->back()->withErrors(['error', 'Erreur lors du traitement: ' . $e->getMessage()]);
        }
    }

    public static function updateClient(Request $request, Client $client)
    {
        DB::beginTransaction();

        try {

            if($request->has('nom_entreprise')  && $request->input('nom_entreprise')) {

                ValidationHelper::validateEntrepriseClient($request, $client->id);

                $client->name = $request->input('nom_entreprise');
                $client->ICE = $request->input('ICE');
                $client->phone = $request->input('phone_contact');
                $client->driver_name = $request->input('driver_name');
                $client->entreprise_yn = '1';

            } else {

                ValidationHelper::validateSimpleClient($request);

                $client->name = $request->input('name');
                $client->phone = $request->input('phone');
            }

            if ($client->user){
                $client->user->phone = $client->phone;
                $client->user->name = $client->name;
                $client->user->save();
            }

            if ($request->has('nouveau_compte')) {

                ValidationHelper::validateNewUser($request);

                $user = User::create([
                    'name' => $client->name,
                    'phone' => $client->phone,
                    'username' => $request->input('username'),
                    'gendre' => $request->input('gendre'),
                    'role_id' => '3',
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'adresse' => $request->input('adresse'),
                ]);

                $client->user_id = $user->id;
            }

            $client->save();

            DB::commit();

            return $client;
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            // Gérez l'erreur ou redirigez vers une page d'erreur
            return redirect()->back()->withErrors(['error', 'Erreur lors du traitement: ' . $e->getMessage()]);
        }
    }
}
