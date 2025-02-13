<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\PageRepository;
use App\Entities\User;

class UserController extends AbstractController {
    private UserRepository $userRepo;
    private PageRepository $pageRepo;
    private AuthController $auth;

    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->pageRepo = new PageRepository();
        $this->auth = new AuthController();
    }

    public function listUsers(): void {
        if (!$this->auth->isAdmin()) {
            die("❌ Accès refusé !");
        }

        $users = $this->userRepo->findAll();
        $this->render('admin_users', ['users' => $users]);
    }

    public function changeRole(int $userId): void {
        if (!$this->auth->isAdmin()) {
            die("❌ Accès refusé !");
        }

        $user = $this->userRepo->findById($userId);
        if (!$user) {
            die("❌ Utilisateur introuvable.");
        }

        $newRole = ($user->getRole() === 'user') ? 'admin' : 'user';
        $this->userRepo->updateRole($userId, $newRole);

        $this->redirect('/admin/users');
    }

    public function deleteUser(int $userId): void {
        if (!$this->auth->isAdmin()) {
            die("❌ Accès refusé !");
        }

        $user = $this->userRepo->findById($userId);
        if (!$user) {
            die("❌ Utilisateur introuvable.");
        }

        $admin = $this->userRepo->findAdmin();
        if (!$admin) {
            die("❌ Aucun administrateur disponible pour récupérer les pages.");
        }

        $this->pageRepo->reassignPages($userId, $admin->getId());

        $this->userRepo->delete($userId);

        $this->redirect('/admin/users');
    }
}
