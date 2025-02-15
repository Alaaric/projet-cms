<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Exception;

class AuthController extends AbstractController {
    private UserRepository $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function login(): void {
        try {
            if ($this->isRequestMethod('POST')) {
                $user = $this->userRepo->findByEmail($this->getInput('email'));

                if ($user && password_verify($this->getInput('password'), $user->getPassword())) {
                    $_SESSION['user'] = [
                        'id' => $user->getId(),
                        'username' => $user->getUsername(),
                        'role' => $user->getRole(),
                    ];
                    $this->redirect('/');
                } else {
                    $error = "Identifiants incorrects.";
                }
            }
            $this->render('login', ['error' => $error ?? null]);
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la connexion : " . $e->getMessage()]);
        }
    }

    public function logout(): void {
        try {
            session_unset();
            session_destroy();
            $this->render('login');
            exit();
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la déconnexion : " . $e->getMessage()]);
        }
    }

    public function getUser(): ?array {
        return $_SESSION['user'] ?? null;
    }

    public function isAdmin(): bool {
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin');
    }
}