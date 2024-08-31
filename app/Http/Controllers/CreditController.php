<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UploadController;
use App\Http\Requests\UpdateCreditRequest;
use App\Models\Credit;
use App\Models\CreditLine;
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

    public function nonPaid()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $credits = Credit::whereColumn('total', '<>', 'paid')->get();

        return view('backOffice.credits.non_paid_credits', compact('credits'));
    }


    public function payments(Credit $credit)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.credits.payments', compact('credit'));
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

        $creditLine = new CreditLine();
        $creditLine->amount = $creditData["paid"];
        $creditLine->comment = $creditData["comment"];
        $creditLine->credit_id = $credit->id;

        if ($request->hasFile('documents')) {
            $creditLine->document = UploadController::creditDoc($request);
        }

        $creditLine->save();

        $credit->paid += $creditLine->amount;

        $credit->save();

        return redirect()->route('credits.index')->with('success', 'Credit modifié avec succès!');
    }
}
