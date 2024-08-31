<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UploadController;
use App\Http\Controllers\Helper\ValidationHelper;
use App\Http\Requests\UpdateCreditRequest;
use App\Models\Credit;
use App\Models\CreditLine;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $products = Product::all();

        return view('backOffice.products.index', compact('products'));
    }

    public function create()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        return view('backOffice.products.create');
    }



    public function store(Request $request)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();

        try {

            ValidationHelper::validateProduct( $request, null);

            $product = new Product();
            $product->label = $request->input('label');
            $product->ref = $request->input('ref');
            $product->Qte = $request->input('Qte') ?? 0;

            $product->save();

            DB::commit();
            return redirect()->route('products.index');
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Erreur lors du traitement: ' . $e->getMessage());

            // Gérez l'erreur ou redirigez vers une page d'erreur
            return redirect()->back()->withErrors(['error', 'Erreur lors du traitement: ' . $e->getMessage()]);
        }
    }


    public function edit(Product $product)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();

        try {

            ValidationHelper::validateProduct( $request, $product->id);

            $product->label = $request->input('label');
            $product->ref = $request->input('ref');
            $product->Qte = $request->input('Qte') ?? 0;

            $product->save();

            DB::commit();
            return redirect()->route('products.index');
        }
        catch (\Exception $e) {

            DB::rollBack();
            Log::error('Erreur lors du traitement: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error', 'Erreur lors du traitement: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();
        try {

            $product->delete();
            DB::commit();
            return redirect()->route('products.index');
        }
        catch (\Exception $e) {

            DB::rollBack();
            Log::error('Erreur lors du traitement: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error', 'Erreur lors du traitement: ' . $e->getMessage()]);
        }
    }
}
