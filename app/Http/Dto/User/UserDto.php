<?php

namespace App\Http\Dto\User;

class UserDto
{

    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $username,
        public string $email,
        public string $password
    ) {
    }

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
