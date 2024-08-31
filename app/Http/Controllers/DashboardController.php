<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Credit;
use App\Models\SupplierCredit;
use App\Models\Employee;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Number of clients

        $credits = Credit::whereColumn('total', '<>', 'paid')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        $suplierCredits = SupplierCredit::whereColumn('total', '<>', 'paid')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();


        return view('dashboard', compact('credits', 'suplierCredits'));
    }

    public function getCounts()
    {
        $currentClientCount = Client::count();
        $ClientChange = Client::where('created_at', '>', Carbon::now()->subWeek())->count();
//        $clientChangePercent = $lastWeekClientCount > 0 ? (($currentClientCount - $lastWeekClientCount) / $lastWeekClientCount) * 100 : 0;

        $currentEmployeeCount = Employee::count();
        $EmployeeChange = Employee::where('created_at', '>', Carbon::now()->subWeek())->count();
//        $employeeChangePercent = $lastWeekEmployeeCount > 0 ? (($currentEmployeeCount - $lastWeekEmployeeCount) / $lastWeekEmployeeCount) * 100 : 0;

        $currentSupplierCount = Provider::count();
        $SupplierChange = Provider::where('created_at', '>', Carbon::now()->subWeek())->count();
//        $supplierChangePercent = $lastWeekSupplierCount > 0 ? (($currentSupplierCount - $lastWeekSupplierCount) / $lastWeekSupplierCount) * 100 : 0;

        $currentQuotationCount = Quotation::count();
        $QuotationChange = Quotation::where('created_at', '>', Carbon::now()->subWeek())->count();

        $currentProductCount = Product::count();
        $ProductChange = Product::where('created_at', '>', Carbon::now()->subWeek())->count();

        return response()->json([
            'clients' => [
                'count' => $currentClientCount,
                'change' => $ClientChange
            ],
            'employees' => [
                'count' => $currentEmployeeCount,
                'change' => $EmployeeChange
            ],
            'suppliers' => [
                'count' => $currentSupplierCount,
                'change' => $SupplierChange
            ],
            'quotations' => [
                'count' => $currentQuotationCount,
                'change' => $QuotationChange
            ],
            'products' => [
                'count' => $currentProductCount,
                'change' => $ProductChange
            ]
        ]);
    }

    public function getQuotationsByMonth()
    {
        $now = Carbon::now();
        $sixMonthsAgo = $now->copy()->subMonths(6);

        $months = [];
        for ($date = $sixMonthsAgo; $date <= $now; $date->addMonth()) {
            $months[$date->format('Y-m')] = [
                'status_0' => 0,
                'status_1' => 0
            ];
        }

        $formattedDate = $sixMonthsAgo->format('Y-m-d H:i:s');

        $orders = Quotation::where('created_at', '>=', '\'' . $formattedDate . '\'')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, is_active, count(*) as count')
            ->groupBy('month', 'is_active')
            ->orderBy('month')
            ->get();

        foreach ($orders as $order) {
            if ($order->is_active == 0) {
                $months[$order->month]['status_0'] = $order->count;
            } else if ($order->is_active == 1) {
                $months[$order->month]['status_1'] = $order->count;
            }
        }

//        return $months;

        $formattedOrders = [];
        foreach ($months as $month => $counts) {
            $formattedOrders[] = [
                'month' => $month,
                'status_0' => $counts['status_0'],
                'status_1' => $counts['status_1']
            ];
        }

        return response()->json($formattedOrders);
    }
}
