<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $employees = Employee::all();

        return view('backOffice.employees.index', compact('employees'));
    }
}
