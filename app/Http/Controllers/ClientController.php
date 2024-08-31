<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\CommonActionsHelper;
use App\Http\Controllers\Helper\UploadController;
use App\Http\Controllers\Helper\ValidationHelper;
use App\Http\Requests\UpdateCreditRequest;
use App\Models\Client;
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

class ClientController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $clients = Client::all();

        return view('backOffice.clients.index', compact('clients'));
    }

    public function create()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('backOffice.clients.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client = CommonActionsHelper::createClient($request);

        if ($client instanceof Client) {
            return redirect()->route('clients.index');
        } else {
            return $client;
        }

    }


    public function edit(Client $client)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $originClient)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client = CommonActionsHelper::updateClient($request, $originClient);

        if ($client instanceof Client) {
            return redirect()->route('clients.index');
        } else {
            return $client;
        }
    }

    public function destroy(Client $client)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $client->user()->delete();
        $client->delete();

        return redirect()->route('clients.index');
    }
}
