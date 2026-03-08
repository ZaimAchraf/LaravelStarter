<?php

namespace App\DTOs;

use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;

class UserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $username,
        public ?string $password = null,
        public ?string $phone = null,
        public int $roleId = 3,
        public ?UploadedFile $picture = null,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name: $request->input('name'),
            email: $request->input('email'),
            username: $request->input('username'),
            password: $request->input('password'),
            phone: $request->input('phone'),
            roleId: $request->input('role_id', 3),
            picture: $request->file('picture')
        );
    }
}