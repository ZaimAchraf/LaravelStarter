<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UploadController;
use App\Http\Controllers\Helper\ValidationHelper;
use App\Http\Requests\UpdateCreditRequest;
use App\Models\Credit;
use App\Models\CreditLine;
use App\Models\Employee;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProviderController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $providers = Provider::all();

        return view('backOffice.providers.index', compact('providers'));
    }

    public function create()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('backOffice.providers.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();

        try {
            $provider = new Provider();
            $provider->name = $request->input('name');
            $provider->phone = $request->input('phone');

            if ($request->has('nouveau_compte')) {

                ValidationHelper::validateNewUser($request);

                $user = User::create([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'username' => $request->input('username'),
                    'gendre' => $request->input('gendre'),
                    'role_id' => '5',
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'adresse' => $request->input('adresse'),
                ]);

                $provider->user_id = $user->id;
            }
            $provider->save();

            DB::commit();
            return redirect()->route('providers.index');
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            // Gérez l'erreur ou redirigez vers une page d'erreur
            return redirect()->back()->withErrors(['error', 'Erreur lors du traitement: ' . $e->getMessage()]);
        }
    }


    public function edit(Provider $provider)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();

        try {

            $provider->name = $request->input('name');
            $provider->phone = $request->input('phone');

            if ($request->has('nouveau_compte')) {

                ValidationHelper::validateNewUser($request);

                $user = User::create([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                    'username' => $request->input('username'),
                    'gendre' => $request->input('gendre'),
                    'role_id' => '5',
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'adresse' => $request->input('adresse'),
                ]);

                $provider->user_id = $user->id;
            }
            $provider->save();

            DB::commit();
            return redirect()->route('providers.index');
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            // Gérez l'erreur ou redirigez vers une page d'erreur
            return redirect()->back()->withErrors(['error', 'Erreur lors du traitement: ' . $e->getMessage()]);
        }
    }
}
