<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Helper\UploadController;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ecommerce\ClientUpload;
use App\Models\Employee;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreUserRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all();

        return view('backOffice.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userData = $request->validated();

        unset($userData['fonction']);
        unset($userData['salaire']);

        $user = User::create($userData);
        $user->password = Hash::make($request->input('password'));

        if ($request->hasFile('images')) {
            $user->picture = UploadController::userPic($request);
        }

        $user->save();

        if ($request->filled('fonction')) {
            $employeeData = [
                'fonction' => $request->input('fonction'),
                'salaire' => $request->input('salaire'),
                'user_id' => $user->id, // Associer l'employé à l'utilisateur créé
            ];

            Employee::create($employeeData);
        }




        return redirect()->route('users.index');
    }

    public function show(User $client)
    {
        abort_if(Gate::denies('manage-clients'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('clients.show', compact('client'));
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('backOffice.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $userData = $request->validated();

        unset($userData['fonction']);
        unset($userData['salaire']);

        $user->update($userData);

        if ($request->hasFile('images')) {
            $user->picture = UploadController::userPic($request);
            $user->save();
        }

        if ($request->filled('fonction')) {

            $employee = Employee::where('user_id', $user->id)->get();

            if ($employee){
                $employee->fonction = $request->input('fonction');
                $employee->salaire = $request->input('salaire');
            }else {
                $employeeData = [
                    'fonction' => $request->input('fonction'),
                    'salaire' => $request->input('salaire'),
                    'user_id' => $user->id, // Associer l'employé à l'utilisateur créé
                ];

                Employee::create($employeeData);
            }
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function enable_disable(Request $request, string $user_id)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = User::find($user_id);

        $user->is_active = 1 - $user->is_active;

        $user->save();

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('access-dashboard'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return redirect()->route('users.index');
    }
}
