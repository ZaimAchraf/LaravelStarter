<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCreditRequest;
use App\Models\Credit;
use App\Models\Employee;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CreditController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $credits = Credit::all();

        return view('backOffice.credits.index', compact('credits'));
    }

    public function edit(Credit $credit)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.credits.edit', compact('credit'));
    }

    public function update(UpdateCreditRequest $request, Credit $credit)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $creditData = $request->validated();
        $credit->paid = $creditData["paid"];
        $credit->comment = $creditData["comment"];

        $credit->save();

        return redirect()->route('credits.index')->with('success', 'Credit modifié avec succès!');
    }
}
