<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UploadController;
use App\Http\Requests\UpdateCreditRequest;
use App\Models\Credit;
use App\Models\CreditLine;
use App\Models\Employee;
use App\Models\SupplierCredit;
use App\Models\SupplierCreditLine;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class SupplierCreditController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $credits = SupplierCredit::all();

        return view('backOffice.supplier_credits.index', compact('credits'));
    }

    public function edit(SupplierCredit $supplierCredit)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.supplier_credits.edit', compact('supplierCredit'));
    }

    public function payments(SupplierCredit $supplierCredit)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.supplier_credits.payments', compact('supplierCredit'));
    }

    public function update(UpdateCreditRequest $request, SupplierCredit $supplierCredit)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $creditData = $request->validated();

        $creditLine = new SupplierCreditLine();
        $creditLine->amount = $creditData["paid"];
        $creditLine->comment = $creditData["comment"];
        $creditLine->supplier_credit_id = $supplierCredit->id;

        if ($request->hasFile('documents')) {
            $creditLine->document = UploadController::supplierCreditDoc($request);
        }

        $creditLine->save();

        $supplierCredit->paid += $creditLine->amount;

        $supplierCredit->save();

        return redirect()->route('supplierCredits.index')->with('success', 'Credit modifié avec succès!');
    }
}
