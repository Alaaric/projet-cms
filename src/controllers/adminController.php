<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\PageRepository;
use App\Repositories\TemplateRepository;
use Exception;

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
        try {
            if (!$this->auth->isAdmin()) {
                $this->render('403');
                return;
            }

            $pages = $this->pageRepo->findAll();
            $users = $this->userRepo->findAll();
            $template = $this->templateRepo->findAll()[0];

            $this->render('dashboard', ['pages' => $pages, 'users' => $users, 'template' => $template]);
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de l'affichage du tableau de bord : " . $e->getMessage(), 'code' => 500]);
        }
    }

    public function deleteUser(): void {
        try {
            if (!$this->auth->isAdmin()) {
                $this->render('403');
                return;
            }

            $userId = $this->getInput('user_id');
            $user = $this->userRepo->findById($userId);
            if (!$user) {
                $this->render('error', ['message' => "Utilisateur introuvable.", 'code' => 404]);
                return;
            }

            $admin = $this->auth->getUser();
            if (!$admin || $admin['role'] !== 'admin') {
                $this->render('error', ['message' => "Vous devez être admin pour faire cela.", 'code' => 403]);
                return;
            }

            $this->pageRepo->reassignPages($userId, $admin['id']);
            $this->userRepo->delete($userId);
            $this->redirect('/admin/dashboard');
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage(), 'code' => 500]);
        }
    }

    public function deletePage(): void {
        try {
            if (!$this->auth->isAdmin()) {
                $this->render('error', ['message' => "Accès refusé!", 'code' => 403]);
                return;
            }

            $slug = $this->getInput('slug');
            $page = $this->pageRepo->findBySlug($slug);
            if (!$page) {
                $this->render('404');
                return;
            }

            $this->pageRepo->delete($page->getId());
            $this->redirect('/admin/dashboard');
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la suppression de la page : " . $e->getMessage(), 'code' => 500]);
        }
    }

    public function editTemplateStructure(): void {
        try {
            if (!$this->auth->isAdmin()) {
                $this->render('error', ['message' => "Accès refusé!", 'code' => 403]);
                return;
            }

            $template = $this->templateRepo->findAll()[0];

            if ($this->isRequestMethod('POST')) {
                $templateStructure = $this->getInput('template_structure');
                $template->setStructure($templateStructure);
                $this->templateRepo->save($template);
                $this->redirect('/admin/dashboard');
            }

            $this->render('dashboard', ['template' => $template]);
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la modification de la structure du template : " . $e->getMessage(), 'code' => 500]);
        }
    }
}