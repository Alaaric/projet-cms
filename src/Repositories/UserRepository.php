<?php

namespace App\Repositories;

use App\Core\Database;
use App\Entities\User;
use App\Exceptions\Repositories\UserRepositoryException;
use PDO;
use PDOException;

class UserRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /** @return User[] */
    public function findAll(): array {
        try {
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
        } catch (PDOException $e) {
            throw new UserRepositoryException("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
        }
    }

    public function findById(string $id): ?User {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new UserRepositoryException("Utilisateur non trouvé avec l'ID : $id");
            }

            return new User(
                id: $data['id'],
                email: $data['email'],
                username: $data['username'],
                password: $data['password'],
                role: $data['role'],
                createdAt: $data['created_at']
            );
        } catch (PDOException $e) {
            throw new UserRepositoryException("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }

    public function findByEmail(string $email): ?User {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new UserRepositoryException("Utilisateur non trouvé avec l'email : $email");
            }

            return new User(
                id: $data['id'],
                email: $data['email'],
                username: $data['username'],
                password: $data['password'],
                role: $data['role'],
                createdAt: $data['created_at']
            );
        } catch (PDOException $e) {
            throw new UserRepositoryException("Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
        }
    }

    public function save(User $user): void {
        try {
            $stmt = $this->db->prepare("INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user->getEmail(), $user->getUsername(), $user->getPassword(), $user->getRole()]);
        } catch (PDOException $e) {
            throw new UserRepositoryException("Erreur lors de l'enregistrement de l'utilisateur : " . $e->getMessage());
        }
    }

    public function delete(string $id): void {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new UserRepositoryException("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
        }
    }
}