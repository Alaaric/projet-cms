<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\PageRepository;
use App\Repositories\TemplateRepository;

class AdminController extends AbstractController {
    private UserRepository $userRepo;
    private PageRepository $pageRepo;
    private AuthController $auth;
    private TemplateRepository $templateRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->pageRepo = new PageRepository();
        $this->templateRepo = new TemplateRepository();
        $this->auth = new AuthController();
    }
    

    public function dashboard(): void {
        if (!$this->auth->isAdmin()) {
            die(" Accès refusé !");
        }

        $pages = $this->pageRepo->findAll();
        $users = $this->userRepo->findAll();
        $template = $this->templateRepo->findAll()[0]; 

        $this->render('dashboard', ['pages' => $pages, 'users' => $users, 'template' => $template]);
    }

    public function deleteUser(): void {
        if (!$this->auth->isAdmin()) {
            die("Accès refusé !");
        }

        $userId = $this->getInput('user_id');
        $user = $this->userRepo->findById($userId);
        if (!$user) {
            die(" Utilisateur introuvable.");
        }

        $admin = $this->auth->getUser();
        if (!$admin || $admin['role'] !== 'admin') {
            die(" Vous devez être admin pour faire cela.");
        }

        $this->pageRepo->reassignPages($userId, $admin['id']);

        $this->userRepo->delete($userId);

        $this->redirect('/admin/dashboard');
    }

    public function deletePage(): void {
        if (!$this->auth->isAdmin()) {
            die("Accès refusé !");
        }

        $slug = $this->getInput('slug');
        $page = $this->pageRepo->findBySlug($slug);
        if (!$page) {
            die("Page introuvable.");
        }

        $this->pageRepo->delete($page->getId());

        $this->redirect('/admin/dashboard');
    }

    public function editTemplateStructure(): void {
        if (!$this->auth->isAdmin()) {
            die("Accès refusé !");
        }
    
        $template = $this->templateRepo->findAll()[0]; 
    
        if ($this->isRequestMethod('POST')) {
            $templateStructure = $this->getInput('template_structure');
            $template->setStructure($templateStructure);
            $this->templateRepo->save($template);
            $this->redirect('/admin/dashboard'); 
        }
    
        $this->render('dashboard', ['template' => $template]);
    }
}
