<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Credit;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceLine;
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

class InvoiceController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoices = Invoice::all();
        return view('backOffice.invoices.index', compact('invoices'));
    }

    public function getPDF($invoiceID) {

        $invoice = Invoice::find($invoiceID);

//        return view('backOffice.invoices.pdf', ['invoice' => $invoice]);

        $pdf = new Dompdf();
        $pdf->loadHtml(view('backOffice.invoices.pdf', ['invoice' => $invoice]));

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $pdf->setOptions($options);

        $pdf->render();
        return  $pdf->stream(null, ['Attachment' => false]);
    }

    public function create($quotationId)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $quotation = Quotation::find($quotationId);

        return view('backOffice.invoices.create', compact('quotation'));
    }

    private function validateInvoiceLines(Request $request)
    {
        return $request->validate([
            'lines.*.description' => 'required|string|max:255',
            'lines.*.price' => 'required|numeric|min:0',
        ], [
            'lines.*.description.required' => 'La description des ligne de facture est requise.',
            'lines.*.price.required' => 'Le prix des lignes de facture est requis.',
            'lines.*.price.numeric' => 'Le prix de la ligne de quotations doit être un nombre.',
            'lines.*.price.min' => 'Le prix de la ligne de quotations doit être supérieur à 0.',
        ]);
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
//
        try {

//            $this->validateInvoiceLines($request);

            // Enregistrer le quotations avec ses lignes
            $invoice = Invoice::create([
                'quotation_id' => $request->input('quotation'),
                'title' => $request->input('title'),
            ]);

            $total = 0;
            foreach ($request->input('lines') as $lineData) {
                InvoiceLine::create([
                    'invoice_id' => $invoice->id,
                    'description' => $lineData['description'],
                    'price' => $lineData['price'],
                    'type' => $lineData['type'],
                    'TVA' => $lineData['TVA'] ?? 0,
                    'reference' => $lineData['reference'] ?? null,
                    'quantity' => $lineData['quantity'] ?? 1,
                    'state' => $lineData['state'] ?? null
                ]);

                $total += isset($lineData['quantity']) ? $lineData['price'] * $lineData['quantity'] * (1 +($lineData['TVA']/100)) : $lineData['price'] * (1 +($lineData['TVA']/100));
            }

            $invoice->total = $total;
            $invoice->save();

            Log::info('Données sauvegardées avec succès.');
            DB::commit();

            return redirect()->route('quotations.getPDF', $invoice->id);

        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            return redirect()->back()->with('error_message', 'Erreur Technique lors du traitement.');
        }
    }

    public function edit(Invoice $invoice)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.invoices.edit', compact('invoice'));
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();

//        $this->validateInvoiceLines($request);

        $invoice = Invoice::find($request->input('invoiceID'));

        $total = 0;
        foreach ($request->input('lines') as $lineData) {

            InvoiceLine::updateOrCreate([
                'id' => $lineData['id']
            ],
            [
                'invoice_id' => $invoice->id,
                'description' => $lineData['description'],
                'price' => $lineData['price'],
                'type' => $lineData['type'],
                'TVA' => $lineData['TVA'] ?? 0,
                'reference' => $lineData['reference'] ?? null,
                'quantity' => $lineData['quantity'] ?? 1,
                'state' => $lineData['state'] ?? null
            ]);

            $total += isset($lineData['quantity']) ? $lineData['price'] * $lineData['quantity'] * (1 +($lineData['TVA']/100)) : $lineData['price'] * (1 +($lineData['TVA']/100));
        }

        $invoice->total = $total;

        $invoice->save();

        DB::commit();

        return redirect()->back()->with('success', 'Facture a bien été modifiée !');
    }

    public function deleteLine( Request $request )
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');
//        return $request->input('line_id');
        $line = InvoiceLine::findOrFail($request->input('line_id'));
        $price = isset($line->quantity) ? $line->price * $line->quantity * (1 +($line->TVA/100)) : $line->price * (1 +($line->TVA/100));
        $line->invoice->total -= $price;
        $line->invoice->save();
        $line->delete();

        return 'success';
    }

    public function destroy(Invoice $invoice)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoice->invoiceLines()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index');
    }
}
