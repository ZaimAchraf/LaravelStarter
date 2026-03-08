<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::query()->insert([
            ['id' => 1, 'name' => 'SUPER_ADMIN', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'MANAGER', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'USER', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_guest_cannot_access_users_index(): void
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_users_index(): void
    {
        $user = $this->makeUser(3, 'user1@example.com', 'user1');

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertForbidden();
    }

    public function test_manager_can_access_users_index(): void
    {
        $manager = $this->makeUser(2, 'manager@example.com', 'manager');

        $response = $this->actingAs($manager)->get(route('users.index'));

        $response->assertOk();
    }

    public function test_user_can_edit_own_profile_page(): void
    {
        $user = $this->makeUser(3, 'self@example.com', 'self_user');

        $response = $this->actingAs($user)->get(route('users.edit', $user));

        $response->assertOk();
    }

    public function test_user_cannot_edit_other_user_profile_page(): void
    {
        $user = $this->makeUser(3, 'userA@example.com', 'userA');
        $other = $this->makeUser(3, 'userB@example.com', 'userB');

        $response = $this->actingAs($user)->get(route('users.edit', $other));

        $response->assertForbidden();
    }

    public function test_manager_can_toggle_other_user_status(): void
    {
        $manager = $this->makeUser(2, 'manager2@example.com', 'manager2');
        $user = $this->makeUser(3, 'toggle@example.com', 'toggle_user');

        $response = $this->actingAs($manager)->post(route('users.enable_disable', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertFalse($user->fresh()->is_active);
    }

    public function test_user_cannot_toggle_status(): void
    {
        $user = $this->makeUser(3, 'normal@example.com', 'normal_user');
        $other = $this->makeUser(3, 'other@example.com', 'other_user');

        $response = $this->actingAs($user)->post(route('users.enable_disable', $other));

        $response->assertForbidden();
    }

    private function makeUser(int $roleId, string $email, string $username): User
    {
        return User::query()->create([
            'name' => ucfirst($username),
            'email' => $email,
            'username' => $username,
            'password' => Hash::make('password'),
            'role_id' => $roleId,
            'is_active' => true,
        ]);
    }
}
