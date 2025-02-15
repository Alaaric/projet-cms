<?php

namespace App\Controllers;

use App\DTO\UserDTO;
use App\Repositories\UserRepository;
use App\Middleware\AuthMiddleware;
use Exception;

class AuthController extends AbstractController {
    private UserRepository $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function login(): void {
        try {
            if ($this->isRequestMethod(self::METHOD_POST)) {
                $user = $this->userRepo->findByEmail($this->getInput('email'));
                if ($user && password_verify($this->getInput('password'), $user->getPassword())) {
                    $_SESSION[self::USER] = [
                        'id' => $user->getId(),
                        'email' => $user->getEmail(),
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
            AuthMiddleware::checkAuthenticated();

            session_unset();
            session_destroy();
            $this->render('login');
            exit();
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la déconnexion : " . $e->getMessage()]);
        }
    }

    public function getUser(): ?UserDTO {
        if (isset($_SESSION[self::USER])) {
            return new UserDTO(
                $_SESSION[self::USER]['email'],
                $_SESSION[self::USER]['username'],
                $_SESSION[self::USER]['role'],
                $_SESSION[self::USER]['id']
            );
        }
        return null;
    }
}