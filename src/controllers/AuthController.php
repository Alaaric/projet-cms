<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Entities\User;
use App\Core\Auth;
use Exception;

class AuthController extends AbstractController {
    private UserRepository $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function login(): void {
        if ($this->isRequestMethod('POST')) {
            $user = $this->userRepo->findByUsername($this->getInput('username'));
            if ($user && password_verify($this->getInput('password'), $user->getPassword())) {
                Auth::login($user->getId(), $user->getUsername());
                $this->redirect('/');
            } else {
                $error = "Identifiants incorrects.";
            }
        }
        $this->render('login', ['error' => $error ?? null]);
    }

    public function register(): void {
        if ($this->isRequestMethod('POST')) {
            $user = new User(null, $this->getInput('username'), $this->getInput('password'));
            $user->setPassword($this->getInput('password'));
            $this->userRepo->save($user);
            Auth::login($user->getId(), $user->getUsername());
            $this->redirect('/');
        }
        $this->render('register');
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