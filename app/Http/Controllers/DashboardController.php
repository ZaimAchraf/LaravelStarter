<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index()
    {
        if (Gate::denies('access-dashboard')) {
            return redirect()->route('not-authorized');
        }

        return view('dashboard');
    }
}
