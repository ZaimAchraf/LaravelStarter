<?php

namespace App\Services;

use App\DTOs\UserDTO;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(
        protected FileUploadService $uploadService,
    ) {
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById(int $userId): User
    {
        return User::findOrFail($userId);
    }

    public function createFromRequest(StoreUserRequest $request): User
    {
        try {
            Log::info('Creating new user', ['email' => $request->email]);

            $dto = UserDTO::fromRequest($request);
            $user = $this->create($dto);

            Log::info('User created successfully', ['user_id' => $user->id]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Failed to create user', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function create(UserDTO $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'username' => $dto->username,
            'password' => Hash::make($dto->password),
            'phone' => $dto->phone,
            'role_id' => $dto->roleId,
            'is_active' => true,
        ]);

        if ($dto->picture) {
            $user->picture = $this->uploadService->storeUserPicture($dto->picture);
            $user->save();
        }

        return $user;
    }

    public function updateFromRequest(UpdateUserRequest $request, User $user): User
    {
        try {
            Log::info('Updating user', ['user_id' => $user->id]);

            $dto = UserDTO::fromRequest($request);
            $this->update($user, $dto);

            Log::info('User updated successfully', ['user_id' => $user->id]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Failed to update user', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update(User $user, UserDTO $dto): User
    {
        $user->update([
            'name' => $dto->name,
            'email' => $dto->email,
            'username' => $dto->username,
            'phone' => $dto->phone,
            'role_id' => $dto->roleId,
        ]);

        if ($dto->password) {
            $user->password = Hash::make($dto->password);
            $user->save();
        }

        if ($dto->picture) {
            if ($user->picture) {
                $this->uploadService->deleteUserPicture($user->picture);
            }
            $user->picture = $this->uploadService->storeUserPicture($dto->picture);
            $user->save();
        }

        return $user;
    }

    public function toggleActive(User $user): User
    {
        $user->is_active = !$user->is_active;
        $user->save();

        Log::info('User active status toggled', [
            'user_id' => $user->id,
            'is_active' => $user->is_active,
        ]);

        return $user;
    }

    public function delete(User $user): bool
    {
        try {
            Log::info('Deleting user', ['user_id' => $user->id]);

            if ($user->picture) {
                $this->uploadService->deleteUserPicture($user->picture);
            }

            $user->delete();

            Log::info('User deleted successfully', ['user_id' => $user->id]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete user', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}