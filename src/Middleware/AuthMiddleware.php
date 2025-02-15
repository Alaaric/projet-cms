<?php

namespace App\Middleware;

use App\Exceptions\Middleware\AuthException;
use App\Repositories\PageRepository;

class AuthMiddleware {
    public static function checkAuthenticated(): void {
        if (!isset($_SESSION['user'])) {
            throw new AuthException('Vous devez être connecté pour accéder à cette page.');
        }
    }

    public static function checkAdmin(): void {
        self::checkAuthenticated();
        if ($_SESSION['user']['role'] !== 'admin') {
            throw new AuthException('Vous n\'avez pas la permission d\'accéder à cette page.');
        }
    }

    public static function isOwner(string $pageId): bool {
        self::checkAuthenticated();
        $pageRepo = new PageRepository();
        $page = $pageRepo->findById($pageId);
        return $page && $page->getUserId() === $_SESSION['user']['id'];
    }

    public static function isAdmin(): bool {
        self::checkAuthenticated();
        return $_SESSION['user']['role'] === 'admin';
    }
}