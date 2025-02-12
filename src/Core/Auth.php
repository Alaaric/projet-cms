<?php

namespace App\Core;

class Auth {
    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(string $userId, string $username): void {
        self::startSession();
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
    }

    public static function logout(): void {
        self::startSession();
        session_destroy();
    }

    public static function isAuthenticated(): bool {
        self::startSession();
        return isset($_SESSION['user_id']);
    }

    public static function getUser(): ?array {
        self::startSession();
        return self::isAuthenticated() ? $_SESSION : null;
    }
}
