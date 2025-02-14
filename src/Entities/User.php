<?php

namespace App\Entities;

class User {
    public function __construct(
        private string $email,
        private string $username,
        private string $password,
        private string $role = "user",
        private ?string $id = null,
        private ?string $createdAt = null
    ) {}

    public function getId(): ?string {
        return $this->id;
    }

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

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }
}
