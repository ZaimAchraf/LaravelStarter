<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UploadController;
use App\Models\Client;
use App\Models\Credit;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteLine;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Quotation;
use App\Models\QuotationLine;
use App\Models\SupplierCredit;
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

class DeliveryNoteController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryNotes = DeliveryNote::all();
        return view('backOffice.delivery_notes.index', compact('deliveryNotes'));
    }

    public function create($orderID)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order = Order::find($orderID);

        return view('backOffice.delivery_notes.create', compact('order'));
    }

    private function validateNewProvider(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ], [
            'name.required' => 'Le nom du fournisseur est requis.',
            'phone.required' => 'Le numéro Telephone est requis.',
        ]);
    }

    public function show(DeliveryNote $deliveryNote)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.delivery_notes.show', compact('deliveryNote'));
    }


    public function store(Request $request)
    {

        DB::beginTransaction();

        try {

            if ($request->hasFile('documents')) {
                $document = UploadController::deliveryNoteDoc($request);
            }

            $deliveryNote = DeliveryNote::create( [
                'order_id' => $request->input('order'),
                'title' => $request->input('title'),
                'document' => $document ?? null
            ]);

            $order = Order::find($request->input('order'));
            $order->status = $request->input('order_status');
            $order->save();

            foreach ($request->input('lines') as $lineData) {

                if (!$lineData['quantity']) {
                    DB::rollBack();
                    return redirect()->back()->withErrors(['error', 'La quantité est un champ obligatoire.']);
                }

                if ($lineData['id'] != 'deleted') {

                    $orderLine = OrderLine::find($lineData['id']);

                    $sum = 0;
                    foreach ($order->deliveryNotes as $dn) {
                        foreach ($dn->deliveryNoteLines as $dnl) {
                            if ($orderLine->product->id == $dnl->product_id) {
                                $sum += $dnl->Qte;
                            }
                        }
                    }

                    if (($sum + $lineData['quantity']) > $orderLine->Qte) {
                        DB::rollBack();
                        return redirect()->back()->withErrors(['error', 'Vous avez deppasé la quantité spécifiée dans la commande.']);
                    }


//                    DB::rollBack();
//                    return $order->deliveryNotes;

                    DeliveryNoteLine::create([
                        'product_id' => $orderLine->product->id,
                        'delivery_note_id' => $deliveryNote->id,
                        'Qte'   => $lineData['quantity']
                    ]);

                    $orderLine->product->Qte += $lineData['quantity'];
                    $orderLine->product->save();
                }
            }

            if(!$order->credit) {
                $credit = new SupplierCredit();
                $credit->title = $order->title;
                $credit->order_id = $order->id;
                $credit->save();
            }



            Log::info('Données sauvegardées avec succès.');
            DB::commit();

            // Redirection vers une page de confirmation ou de récapitulatif
            return redirect()->route('deliveryNotes.index');

        }
        catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors du traitement: ' . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function destroy(Request $request)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $deliveryNote = DeliveryNote::find($request->input('id'));
        $deliveryNote->deliveryNoteLines()->delete();
        $deliveryNote->delete();

        return redirect()->route('deliveryNotes.index');
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

    public function deleteLine( Request $request )
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ol = OrderLine::findOrFail($request->input('line_id'));
        $ol->delete();

        return 'success';
    }
}
