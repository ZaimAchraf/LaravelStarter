<?php

namespace Tests\Unit;

use App\DTOs\UserDTO;
use App\Models\Role;
use App\Models\User;
use App\Services\FileUploadService;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class UserServiceTest extends TestCase
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

    public function test_create_user_without_picture(): void
    {
        $uploadService = Mockery::mock(FileUploadService::class);
        $uploadService->shouldNotReceive('storeUserPicture');

        $service = new UserService($uploadService);

        $dto = new UserDTO(
            name: 'John Doe',
            email: 'john@example.com',
            username: 'john_doe',
            password: 'secret123',
            phone: '0600000000',
            roleId: 3
        );

        $user = $service->create($dto);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'john@example.com',
            'username' => 'john_doe',
            'role_id' => 3,
            'is_active' => 1,
        ]);
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_create_user_with_picture(): void
    {
        $uploadService = Mockery::mock(FileUploadService::class);
        $uploadService->shouldReceive('storeUserPicture')
            ->once()
            ->andReturn('avatar.png');

        $service = new UserService($uploadService);

        $dto = new UserDTO(
            name: 'Jane Doe',
            email: 'jane@example.com',
            username: 'jane_doe',
            password: 'secret123',
            phone: null,
            roleId: 2,
            picture: UploadedFile::fake()->create('avatar.jpg', 64, 'image/jpeg')
        );

        $user = $service->create($dto);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'picture' => 'avatar.png',
            'role_id' => 2,
        ]);
    }

    public function test_toggle_active_flips_user_status(): void
    {
        $uploadService = Mockery::mock(FileUploadService::class);
        $service = new UserService($uploadService);

        $user = User::query()->create([
            'name' => 'Active User',
            'email' => 'active@example.com',
            'username' => 'active_user',
            'password' => Hash::make('password'),
            'role_id' => 3,
            'is_active' => true,
        ]);

        $service->toggleActive($user);
        $this->assertFalse($user->fresh()->is_active);

        $service->toggleActive($user);
        $this->assertTrue($user->fresh()->is_active);
    }

    public function test_delete_user_with_picture_calls_upload_service_and_deletes_record(): void
    {
        $uploadService = Mockery::mock(FileUploadService::class);
        $uploadService->shouldReceive('deleteUserPicture')
            ->once()
            ->with('avatar.png')
            ->andReturn(true);

        $service = new UserService($uploadService);

        $user = User::query()->create([
            'name' => 'Delete Me',
            'email' => 'delete@example.com',
            'username' => 'delete_me',
            'password' => Hash::make('password'),
            'role_id' => 2,
            'picture' => 'avatar.png',
            'is_active' => true,
        ]);

        $result = $service->delete($user);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
