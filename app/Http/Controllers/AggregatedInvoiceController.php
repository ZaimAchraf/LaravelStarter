<?php

namespace App\Http\Controllers;

use App\Models\AggregatedInvoice;
use App\Models\AggregatedInvoiceLines;
use App\Models\Client;
use App\Models\CProduct;
use App\Models\Credit;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Product;
use App\Models\Folder;
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

class AggregatedInvoiceController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoices = AggregatedInvoice::all();
        return view('backOffice.aggregatedInvoices.index', compact('invoices'));
    }

    public function getPDF($invoiceID) {

        $invoice = AggregatedInvoice::find($invoiceID);

//        return view('backOffice.aggregatedInvoices.pdf', ['invoice' => $invoice]);

        $pdf = new Dompdf();
        $pdf->loadHtml(view('backOffice.aggregatedInvoices.pdf', ['invoice' => $invoice]));

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $pdf->setOptions($options);

        $pdf->render();
        return  $pdf->stream(null, ['Attachment' => false]);
    }

    public function create()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = CProduct::all();

        return view('backOffice.aggregatedInvoices.create', compact( 'products'));
    }

    /**
     * @param $invoice
     * @param mixed $lineData
     * @param $product
     * @return void
     */
    public function createInvoiceLine($invoice, mixed $lineData, $product): void
    {
        AggregatedInvoiceLines::create([
            'invoice_id' => $invoice->id,
            'description' => $lineData['description'] ?? $product->label,
            'price' => $lineData['price'],
            'type' => $lineData['type'],
            'TVA' => $lineData['TVA'] ?? 0,
            'reference' => $product->ref ?? null,
            'quantity' => $lineData['quantity'] ?? 1,
            'state' => $lineData['state'] ?? null
        ]);
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
//        try {

//            $this->validateInvoiceLines($request);

            // Enregistrer le quotations avec ses lignes
            $invoice = AggregatedInvoice::create([
                'client' => $request->input('client'),
                'title' => $request->input('title'),
                'invoice_date' => $request->input('invoice_date'),
                'payments' => $request->input('payments')
            ]);

//            return $invoice;

//            return $request->input('lines');

            $total = 0;
            foreach ($request->input('lines') as $lineData) {

                if ($lineData['type'] == 'Produit') {
                    if (isset($lineData['exist_product'])) {

                        $product = CProduct::find($lineData['exist_product']);
                    }else {
                        return redirect()->back()->withErrors(['error' => 'Il faut obligatoirement choisir un produit existant.']);
                    }

                    if ($product->Qte < $lineData['quantity']) {
                        return redirect()->back()->withErrors(['error' => 'Le stock disponible ne permet pas l\'action courante pour le produit : ' . $product->label]);
                    }
                }

                $this->createInvoiceLine($invoice, $lineData, $product);

                $total += isset($lineData['quantity']) ? $lineData['price'] * $lineData['quantity'] * (1 +($lineData['TVA']/100)) : $lineData['price'] * (1 +($lineData['TVA']/100));
            }

            $invoice->total = $total;
            $invoice->save();

//            return $invoice;

            Log::info('Données sauvegardées avec succès.');
            DB::commit();
//            DB::rollBack();
            return redirect()->route('aggregatedInvoices.index')->with('success', 'Facture a bien été créée !');

//        }
//        catch (\Exception $e) {
//            DB::rollBack();
//
//            Log::error('Erreur lors du traitement: ' . $e->getMessage());
//
//            return redirect()->back()->withErrors(['error' => 'Erreur Technique lors du traitement.']);
//        }
    }

    public function edit($invoiceID)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoice = AggregatedInvoice::find($invoiceID);
        $products = CProduct::all();

        return view('backOffice.aggregatedInvoices.edit', compact('invoice', 'products'));
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();

//        $this->validateInvoiceLines($request);
//        return $request->input('lines');

        $invoice = AggregatedInvoice::find($request->input('invoiceID'));
        if($request->input('number') != $invoice->number && AggregatedInvoice::where('number', $request->input('number'))->count() > 0)  {
            return 'Le numero Facture choisi existe deja !';
//            return redirect()->back()->withErrors(['error' => 'Le numero Facture choisi existe deja !']);
        }

        $total = 0;
        foreach ($request->input('lines') as $lineData) {

            if (isset($lineData['id'])) {

                $line = AggregatedInvoiceLines::find($lineData['id']);

                if ($line->type == 'Produit') {
                    $product = CProduct::where('label', $lineData['description'])->first();

                    $product->Qte += $line->quantity;

                    if ($product->Qte < $lineData['quantity']) {
                        return 'Le stock disponible ne permet pas l\'action courante pour le produit : ' . $product->label;
//                        return redirect()->back()->withErrors(['error' => 'Le stock disponible ne permet pas l\'action courante pour le produit : ' . $product->label]);
                    }

                    $product->Qte -= $lineData['quantity'];
                    $product->save();
                }

                AggregatedInvoiceLines::updateOrCreate([
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
            }else {

                if ($lineData['type'] == 'Produit') {
                    if (isset($lineData['exist_product'])) {

                        $product = CProduct::find($lineData['exist_product']);
                    }else {
                        return 'Il faut obligatoirement choisir un produit existant.';
//                        return redirect()->back()->withErrors(['error' => 'Il faut obligatoirement choisir un produit existant.']);
                    }

                    if ($product->Qte < $lineData['quantity']) {
                        return 'Le stock disponible ne permet pas l\'action courante pour le produit : ' . $product->label;
//                        return redirect()->back()->withErrors(['error' => 'Le stock disponible ne permet pas l\'action courante pour le produit : ' . $product->label]);
                    }

                    $product->Qte -= $lineData['quantity'];
                    $product->save();
                }

                $this->createInvoiceLine($invoice, $lineData, $product);
            }



            $total += isset($lineData['quantity']) ? $lineData['price'] * $lineData['quantity'] * (1 +($lineData['TVA']/100)) : $lineData['price'] * (1 +($lineData['TVA']/100));
        }

        $invoice->total = $total;
        $invoice->invoice_date = $request->input('invoice_date');
        $invoice->payments = $request->input('payments');
        $invoice->number = $request->input('number');

        $invoice->save();

        DB::commit();

        return redirect()->back()->with('success', 'Facture a bien été modifiée !');
    }

    public function deleteLine(Request $request)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Find the line and ensure it exists
        $line = AggregatedInvoiceLines::findOrFail($request->input('line_id'));

        // Check if the related invoice exists
        $invoice = $line->invoice;
        if ($invoice) {
            $price = isset($line->quantity) ? $line->price * $line->quantity * (1 + ($line->TVA / 100)) : $line->price * (1 + ($line->TVA / 100));
            $invoice->total -= $price;
            $invoice->save(); // Save the updated total
        }

        // If the line type is 'Produit', update the related product's quantity
        if ($line->type == 'Produit') {
            $product = CProduct::where('label', $line->description)->first();
            if ($product) {
                $product->Qte += $line->quantity;
                $product->save();
            }
        }

        // Delete the line
        $line->delete();

        return 'success';
    }


    public function destroy( $invoiceID)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $invoice =AggregatedInvoice::find($invoiceID);
        $invoice->invoiceLines()->delete();
        $invoice->delete();

        return redirect()->back()->with('success', 'Facture a bien été suprimée !');
    }
}
