<?php

namespace App\Controllers;

use App\DTO\UserDTO;
use App\DTO\PageDTO;
use App\DTO\Inputs\TemplateInputDTO;
use App\Repositories\UserRepository;
use App\Repositories\PageRepository;
use App\Repositories\TemplateRepository;
use App\Middleware\AuthMiddleware;
use Exception;

class AdminController extends AbstractController {
    private UserRepository $userRepo;
    private PageRepository $pageRepo;
    private TemplateRepository $templateRepo;
    private AuthController $auth;

    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->pageRepo = new PageRepository();
        $this->templateRepo = new TemplateRepository();
        $this->auth = new AuthController();
    }

    public function dashboard(): void {
        try {
            AuthMiddleware::checkAdmin();

            $pages = $this->pageRepo->findAll();
            $users = $this->userRepo->findAll();
            $template = $this->templateRepo->findAll()[0];

            $pageDTOs = array_map(fn($page) => new PageDTO(
                $page->getName(),
                $page->getContent(),
                $page->getUserId(),
                $page->getTemplateId(),
                $page->getSlug(),
                $page->getId(),
                $page->getCreatedAt(),
                $page->getUpdatedAt()
            ), $pages);

            $userDTOs = array_map(fn($user) => new UserDTO(
                $user->getEmail(),
                $user->getUsername(),
                $user->getRole(),
                $user->getId(),
                $user->getCreatedAt()
            ), $users);

            $this->render('dashboard', ['pages' => $pageDTOs, 'users' => $userDTOs, 'template' => $template]);
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de l'affichage du tableau de bord : " . $e->getMessage(), 'code' => 500]);
        }
    }

    public function deleteUser(): void {
        try {
            AuthMiddleware::checkAdmin();

            $userId = $this->getInput(self::INPUT_KEY_USER_ID);
            $user = $this->userRepo->findById($userId);
            if (!$user) {
                $this->render('error', ['message' => "Utilisateur introuvable.", 'code' => 404]);
                return;
            }

            $admin = $this->auth->getUser();
            if (!$admin || $admin->getRole() !== 'admin') {
                $this->render('error', ['message' => "Vous devez être admin pour faire cela.", 'code' => 403]);
                return;
            }

            $this->pageRepo->reassignPages($userId, $admin->getId());
            $this->userRepo->delete($userId);
            $this->redirect('/admin/dashboard');
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage(), 'code' => 500]);
        }
    }

    public function deletePage(): void {
        try {
            AuthMiddleware::checkAdmin();

            $slug = $this->getInput(self::INPUT_KEY_SLUG);
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
            AuthMiddleware::checkAdmin();

            $template = $this->templateRepo->findAll()[0];

            if ($this->isRequestMethod(self::METHOD_POST)) {
                $templateStructure = $this->getInput('template_structure');
                $templateInputDTO = new TemplateInputDTO(
                    $template->getName(),
                    $templateStructure
                );
                $this->templateRepo->update($template->getId(), $templateInputDTO);
                $this->redirect('/admin/dashboard');
            }

            $this->render('dashboard', ['template' => $template]);
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la modification de la structure du template : " . $e->getMessage(), 'code' => 500]);
        }
    }
}