<?php

namespace App\Controllers;

use App\Repositories\PageRepository;
use App\Entities\Page;

class PageController extends AbstractController {
    private PageRepository $pageRepo;

    public function __construct() {
        $this->pageRepo = new PageRepository();
    }

    public function index(): void {
        $pages = $this->pageRepo->findAll();
        $this->render('pages', ['pages' => $pages]);
    }

    public function show(string $slug): void {
        $page = $this->pageRepo->findBySlug($slug);
        require_once '../src/views/page.php';
    }

    public function create(): void {
        if ($this->isRequestMethod('POST')) {
            var_dump($this->getInput());
            $page = new Page( $this->getInput('title'), $this->getInput('content'), $_SESSION['user']['id'], $this->getInput('slug'));
            $this->pageRepo->save($page);
            $this->redirect('/');
        }
        $this->render('pagesCreation');
    }
}