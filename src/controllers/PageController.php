<?php

namespace App\Controllers;

use App\Repositories\PageRepository;
use App\Entities\Page;
use App\Entities\Header;
use App\Entities\Body;
use App\Entities\Footer;

class PageController extends AbstractController {
    private PageRepository $pageRepo;
    private AuthController $auth;

    public function __construct() {
        $this->pageRepo = new PageRepository();
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

        extract(['page' => $page]);

        require_once '../src/Views/page.php';

    }

    public function create(): void {

        if ($this->isRequestMethod('POST')) {
            $user = $this->auth->getUser();
            $page = new Page(
                id: null,
                title: $this->getInput('title'),
                slug: $this->getInput('slug'),
                header: new Header($this->getInput('header')),
                body: new Body($this->getInput('body')),
                footer: new Footer($this->getInput('footer')),
                userId: $user['id']
            );

            $this->pageRepo->save($page);
            $this->redirect('/');
        }

        $this->render('pagesCreation');
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

        if ($this->isRequestMethod('POST')) {
            $page->setTitle($this->getInput('title'));
            $page->setSlug($this->getInput('slug', ), $this->pageRepo);

            $page->getHeader()->setContent($this->getInput('header'));
            $page->getBody()->setContent($this->getInput('body'));
            $page->getFooter()->setContent($this->getInput('footer'));

            if ($isAdmin) {
                $page->getHeader()->setStructure($this->getInput('header_structure') ?: null);
                $page->getBody()->setStructure($this->getInput('body_structure') ?: null);
                $page->getFooter()->setStructure($this->getInput('footer_structure') ?: null);
            }

            $this->pageRepo->save($page);
            $this->redirect("/page/" . $page->getSlug());
        }

        $this->render('pageCreation', ['page' => $page, 'isAdmin' => $isAdmin]);
    }
}
