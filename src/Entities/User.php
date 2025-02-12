<?php

namespace App\Entities;

class User {
    public function __construct(
        private ?int $id = null,
        private string $email,
        private string $username,
        private string $password,
        private string $role = "user",
        private ?string $createdAt = null
    ) {}

    public function getId(): ?int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }
}
