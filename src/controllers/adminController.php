<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\PageRepository;
use App\Entities\User;

class AdminController extends AbstractController {
    private UserRepository $userRepo;
    private PageRepository $pageRepo;
    private AuthController $auth;

    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->pageRepo = new PageRepository();
        $this->auth = new AuthController();
    }

    public function dashboard(): void {
        if (!$this->auth->isAdmin()) {
            die("❌ Accès refusé !");
        }

        $pages = $this->pageRepo->findAll();
        $users = $this->userRepo->findAll();
        $this->render('admin_dashboard', ['pages' => $pages, 'users' => $users]);
    }

    public function deleteUser(int $userId): void {
        if (!$this->auth->isAdmin()) {
            die("❌ Accès refusé !");
        }

        $user = $this->userRepo->findById($userId);
        if (!$user) {
            die("❌ Utilisateur introuvable.");
        }

        // Récupérer l'admin connecté
        $admin = $this->auth->getUser();
        if (!$admin || $admin['role'] !== 'admin') {
            die("❌ Vous devez être admin pour faire cela.");
        }

        // Réassigner les pages de l'utilisateur supprimé à l'admin connecté
        $this->pageRepo->reassignPages($userId, $admin['id']);

        // Supprimer l'utilisateur
        $this->userRepo->delete($userId);

        $this->redirect('/admin/dashboard');
    }
}
