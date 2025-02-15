<?php

namespace App\DTO\Inputs;

class UserInputDTO {
    public function __construct(
        private string $email,
        private string $username,
        private string $password,
        private string $role
    ) {}

    public function getEmail(): string {
        return $this->email;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRole(): string {
        return $this->role;
    }
}