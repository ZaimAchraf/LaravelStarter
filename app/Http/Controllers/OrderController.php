<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Credit;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Product;
use App\Models\Provider;
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

class OrderController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $orders = Order::all();
        return view('backOffice.orders.index', compact('orders'));
    }

    public function create()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $providers = Provider::all();
        $products = Product::all();

        return view('backOffice.orders.create', compact('providers', 'products'));
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

    public function show(Order $order)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('backOffice.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all();

        return view('backOffice.orders.edit', compact('order', 'products'));
    }

    private function validateOrderLines(Request $request, $index)
    {
        return $request->validate([
            'lines.' . $index . '.label' => 'required|string|max:255',
            'lines.' . $index . '.ref' => 'string|unique:products,ref',
        ], [
            'lines.' . $index . '.label.required' => 'La description de la ligne de commande est requise.',
            'lines.' . $index . '.ref.unique' => 'La reference de la ligne de commande doit être unique.',
        ]);
    }


    public function store(Request $request)
    {

        DB::beginTransaction();
//
        try {


            if ($request->has('new_provider')) {

                $this->validateNewProvider($request);

                $provider = Provider::Create([
                    'name' => $request->input('name'),
                    'phone' => $request->input('phone'),
                ]);

            } else {
                $request->validate([
                    'exist_provider' => 'required|string|exists:providers,id',
                ], [
                    'exist_provider.required' => 'Le choix du fournisseur est obligatoire.',
                    'exist_provider.exists' => 'Le Fournisseur choisi n\'existe pas dans la base de données.',
                ]);
                $provider = Provider::find($request->input('exist_provider'));
            }

            $order = Order::create([
                'provider_id' => $provider->id,
                'title' => $request->input('title'),
            ]);

            foreach ($request->input('lines') as $index => $lineData) {

                if (isset($lineData['new_product'])) {

                    $this->validateOrderLines($request, $index);

                    $product = Product::find($lineData['ref']);

                    if ($product) {

                        $product->label = $lineData['label'];

                    } else {

                        $product = new Product();
                        $product->ref = $lineData['ref'];
                        $product->label = $lineData['label'];
                    }

                    $product->save();

                }else {

                    $product = Product::find($lineData['exist_product']);
                }

                OrderLine::create([
                    'product_id' => $product->id,
                    'order_id'   => $order->id,
                    'Qte'   => $lineData['quantity'] ?? 1
                ]);
            }

            Log::info('Données sauvegardées avec succès.');
            DB::commit();

            // Redirection vers une page de confirmation ou de récapitulatif
            return redirect()->route('orders.index');

//        } catch (\Illuminate\Validation\ValidationException $e) {
//            DB::rollback();
//            return response()->json(['errors' => $e->errors()], 422);
//        }
        }
        catch (\Exception $e) {

            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error', 'Erreur technique lors du traitement ' . $e->getMessage()]);
        }
    }

    public function getPDF($idQuotation) {

        $quotation = Quotation::find($idQuotation);

//        return view('backOffice.quotations.pdf', ['quotation' => $quotation]);

        $pdf = new Dompdf();
        $pdf->loadHtml(view('backOffice.quotations.pdf', ['quotation' => $quotation]));

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $pdf->setOptions($options);

        $pdf->render();
        return  $pdf->stream(null, ['Attachment' => false]);
    }

    public function destroy(Order $order)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->orderLines()->delete();
        $order->delete();

        return redirect()->route('orders.index');
    }

    public function changeStatus(Request $request)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order = Order::find($request->input('id'));

        $order->status = $request->input('status');

        $order->save();


        return redirect()->route('orders.index');
    }

    public function update(Request $request, Order $order)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $order->title = $request->input('title');
        foreach ($request->input('lines') as $lineData) {

            if (array_key_exists('new_product', $lineData) && $lineData['new_product']) {

//                $this->validateOrderLines($request);

                $product = Product::find($lineData['ref']);

                if ($product) {

                    $product->label = $lineData['label'];
                    $product->Qte += $lineData['quantity'];

                } else {

                    $product = new Product();
                    $product->ref = $lineData['ref'];
                    $product->label = $lineData['label'];
                    $product->Qte = $lineData['quantity'];

                }

                $product->save();

            }else {

                $product = Product::find($lineData['exist_product']);
            }

            if (array_key_exists('id', $lineData) && $lineData['id']) {
                $orderLine = OrderLine::find($lineData['id']);

                if ($orderLine) {
                    $orderLine->product_id = $product->id;
                    $orderLine->order_id = $order->id;
                    $orderLine->Qte = $lineData['quantity'] ?? 1;
                    $orderLine->save();
                } else {
                    return redirect()->back()->withErrors(['error' => 'La ligne de commande n\'a pas été trouvée dans la bd.']);
                }
            }else {

                OrderLine::create([
                    'product_id' => $product->id,
                    'order_id'   => $order->id,
                    'Qte'   => $lineData['quantity'] ?? 1
                ]);
            }
        }

        $order->save();

        return redirect()->back()->with('success', 'Order bien modifié !');
    }

    public function deleteLine( Request $request )
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ol = OrderLine::findOrFail($request->input('line_id'));
        $ol->delete();

        return 'success';
    }
}
