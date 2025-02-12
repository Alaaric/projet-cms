<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Entities\User;
use Exception;

class AuthController extends AbstractController {
    private UserRepository $userRepo;


    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function login(): void {
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
    }

    public function logout()
    {
        try {
            session_unset();
            session_destroy();
            $this->render('login');
            exit();
        } catch (Exception $e) {
            $this->render('error', ['message' => 'Une erreur est survenue lors de la déconnexion.']);
        }
    }
}