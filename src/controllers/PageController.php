<?php

namespace App\Controllers;

use App\Repositories\PageRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\UserRepository;
use App\Entities\Page;

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
        $pages = $this->pageRepo->findAll();
        $this->render('pages', ['pages' => $pages]);
    }

    public function show(string $slug): void {
        $page = $this->pageRepo->findBySlug($slug);
        if (!$page) {
            $this->render('error', ['message' => 'Page non trouvée.']);
            return;
        }

        $template = $this->templateRepo->findById($page->getTemplateId());
        if (!$template) {
            $this->render('error', ['message' => 'Template non trouvé.']);
            return;
        }

        $this->render('page', ['page' => $page, 'template' => $template]);
    }

    public function create(): void {
        $template = $this->templateRepo->findAll()[0];

        if (!$template) {
            $this->render('error', ['message' => 'Template non trouvé.']);
            return;
        }

        preg_match_all('/{{(.*?)}}/', $template->getStructure(), $matches);
        $placeholders = $matches[1];

        if ($this->isRequestMethod('POST')) {
            $user = $this->auth->getUser();

            // Vérifiez que l'utilisateur existe
            $existingUser = $this->userRepo->findById($user['id']);
            if (!$existingUser) {
                $this->render('error', ['message' => 'Utilisateur non trouvé.']);
                return;
            }

            $content = [];
            foreach ($placeholders as $placeholder) {
                $content[$placeholder] = $this->getInput($placeholder);
            }

            $templateStructure = $this->getInput('template_structure');
            $template->setStructure($templateStructure);
            $this->templateRepo->save($template);

            $page = new Page(
                title: $this->getInput('title'),
                slug: $this->getInput('slug'),
                content: $content,
                userId: $user['id'],
                templateId: $template->getId()
            );

            $this->pageRepo->save($page);
            $this->redirect('/');
        }

        $this->render('pagesCreation', ['placeholders' => $placeholders, 'template' => $template]);
    }

    public function edit(string $slug): void {
        $page = $this->pageRepo->findBySlug($slug);
        if (!$page) {
            die("Page introuvable.");
        }

        $user = $this->auth->getUser();
        $isAdmin = $this->auth->isAdmin();

        if (!$isAdmin && $page->getUserId() !== $user['id']) {
            die("Vous ne pouvez pas modifier cette page.");
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

            $templateStructure = $this->getInput('template_structure');
            $template->setStructure($templateStructure);
            $this->templateRepo->save($template);

            $page->setTitle($this->getInput('title'));
            $page->setSlug($this->getInput('slug'), $this->pageRepo);
            $page->setContent($content);

            $this->pageRepo->save($page);
            $this->redirect("/page/" . $page->getSlug());
        }

        $this->render('pagesCreation', ['page' => $page, 'placeholders' => $placeholders, 'template' => $template]);
    }
}