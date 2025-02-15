<?php

namespace App\Controllers;

use App\DTO\PageDTO;
use App\DTO\Inputs\PageInputDTO;
use App\Repositories\PageRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\UserRepository;
use Exception;

class PageController extends AbstractController {
    private PageRepository $pageRepo;
    private TemplateRepository $templateRepo;
    private UserRepository $userRepo;
    private AuthController $auth;

    public function __construct() {
        $this->pageRepo = new PageRepository();
        $this->templateRepo = new TemplateRepository();
        $this->userRepo = new UserRepository();
        $this->auth = new AuthController();
    }

    public function index(): void {
        try {
            $pages = $this->pageRepo->findAll();
            $this->render('pages', ['pages' => $pages]);
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la récupération des pages : " . $e->getMessage()]);
        }
    }

    public function show(string $slug): void {
        try {
            $page = $this->pageRepo->findBySlug($slug);
            if (!$page) {
                $this->render('404', ['message' => 'Page non trouvée.']);
                return;
            }

            $template = $this->templateRepo->findById($page->getTemplateId());
            if (!$template) {
                $this->render('error', ['message' => 'Template non trouvé.']);
                return;
            }

            extract(['page' => $page]);
            require_once '../src/Views/page.php';
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de l'affichage de la page : " . $e->getMessage()]);
        }
    }

    public function create(): void {
        try {

            $user = $this->auth->getUser();
            if (!$user) {
                $this->render('403', ['message' => 'Vous devez être connecté pour créer une page.']);
                return;
            }

            $template = $this->templateRepo->findAll()[0];

            if (!$template) {
                $this->render('error', ['message' => 'Template non trouvé.']);
                return;
            }

            preg_match_all('/{{(.*?)}}/', $template->getStructure(), $matches);
            $placeholders = $matches[1];

            if ($this->isRequestMethod('POST')) {
                $existingUser = $this->userRepo->findById($user->getId());
                if (!$existingUser) {
                    $this->render('error', ['message' => 'Utilisateur non trouvé.']);
                    return;
                }

                $content = [];
                foreach ($placeholders as $placeholder) {
                    $content[$placeholder] = $this->getInput($placeholder);
                }

                $pageInputDTO = new PageInputDTO(
                    $this->getInput('name'),
                    $this->getInput('slug'),
                    $content,
                    $user->getId(),
                    $template->getId()
                );

                $this->pageRepo->save($pageInputDTO);
                $this->redirect("/page/" . $pageInputDTO->getSlug());
            }

            $this->render('pagesCreation', ['placeholders' => $placeholders, 'template' => $template]);
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la création de la page : " . $e->getMessage()]);
        }
    }

    public function edit(string $slug): void {
        try {

            $user = $this->auth->getUser();
            if (!$user) {
                $this->render('403', ['message' => 'Vous devez être connecté pour modifier une page.']);
                return;
            }

            $page = $this->pageRepo->findBySlug($slug);
            if (!$page) {
                $this->render('error', ['message' => 'Page introuvable.']);
                return;
            }

            $isAdmin = $this->auth->isAdmin();

            if (!$isAdmin && $page->getUserId() !== $user->getId()) {
                $this->render('error', ['message' => 'Vous ne pouvez pas modifier cette page.']);
                return;
            }

            $template = $this->templateRepo->findById($page->getTemplateId());

            if (!$template) {
                $this->render('error', ['message' => 'Template non trouvé.']);
                return;
            }

            preg_match_all('/{{(.*?)}}/', $template->getStructure(), $matches);
            $placeholders = $matches[1];

            if ($this->isRequestMethod('POST')) {
                $content = [];
                foreach ($placeholders as $placeholder) {
                    $content[$placeholder] = $this->getInput($placeholder);
                }

                $pageInputDTO = new PageInputDTO(
                    $this->getInput('name'),
                    $this->getInput('slug'),
                    $content,
                    $user->getId(),
                    $template->getId()
                );

                $this->pageRepo->update($page->getId(), $pageInputDTO);
                $this->redirect("/page/" . $pageInputDTO->getSlug());
            }

            $pageDTO = new PageDTO(
                $page->getName(),
                $page->getContent(),
                $page->getUserId(),
                $page->getTemplateId(),
                $page->getSlug(),
                $page->getId(),
                $page->getCreatedAt(),
                $page->getUpdatedAt()
            );

            $this->render('pagesCreation', ['page' => $pageDTO, 'placeholders' => $placeholders, 'template' => $template]);
        } catch (Exception $e) {
            $this->render('error', ['message' => "Erreur lors de la modification de la page : " . $e->getMessage()]);
        }
    }
}