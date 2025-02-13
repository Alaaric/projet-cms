<?php

namespace App\Repositories;

use App\Core\Database;
use App\Entities\User;
use PDO;

class UserRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

        /**  @return User[] */
    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM users");
        $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($data) => new User(
            id: $data['id'],
            email: $data['email'],
            username: $data['username'],
            password: $data['password'],
            role: $data['role'],
            createdAt: $data['created_at']
        ), $usersData);
    }

    public function findById(int $id): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new User(
            id: $data['id'],
            email: $data['email'],
            username: $data['username'],
            password: $data['password'],
            role: $data['role'],
            createdAt: $data['created_at']
        ) : null;
    }
    
    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new User($data['email'], $data['username'], $data['password'], $data['role'], $data['id'], $data['created_at']) : null;
    }

    public function updateRole(int $id, string $role): void {
        $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$role, $id]);
    }

    public function save(User $user): void {
        $stmt = $this->db->prepare("INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user->getEmail(), $user->getUsername(), $user->getPassword(), $user->getRole()]);
    }

    public function findAdmin(): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data['id'], $data['email'], $data['username'], $data['password'], $data['role'], $data['created_at']) : null;
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
}