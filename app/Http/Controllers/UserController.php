<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected UserService $service)
    {
    }

    public function index()
    {
        $this->authorize('viewAny', User::class); 
        $users = $this->service->getAllUsers();

        return view('backOffice.users.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class); // Use policy

        return view('backOffice.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        $this->service->createFromRequest($request);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user); 

        return $user;
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user); 

        return view('backOffice.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        $this->service->updateFromRequest($request, $user);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        $this->authorize('toggleStatus', $user); 
        $this->service->toggleActive($user);

        return redirect()->route('users.index')->with('success', 'User status updated!');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user); 
        $this->service->delete($user);

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
