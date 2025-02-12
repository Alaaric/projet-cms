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

    public function findByUsername(string $username): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new User($data['id'], $data['username'], $data['password']) : null;
    }
    
    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new User($data['email'], $data['username'], $data['password'], $data['role'], $data['id'], $data['created_at']) : null;
    }

    public function save(User $user): void {
        $stmt = $this->db->prepare("INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user->getEmail(), $user->getUsername(), $user->getPassword(), $user->getRole()]);
    }

    public function deleteUser($userId, $adminId)
    {
        $this->reassignPagesToAdmin($userId, $adminId);

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
    }

    private function reassignPagesToAdmin($userId, $adminId)
    {
        $stmt = $this->db->prepare("UPDATE pages SET user_id = :admin_id WHERE user_id = :user_id");
        $stmt->execute(['admin_id' => $adminId, 'user_id' => $userId]);
    }
}