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

    public function create(): void {
        if ($this->isRequestMethod('POST')) {
            $page = new Page(null, $this->getInput('title'), $this->getInput('content'));
            $this->pageRepo->save($page);
            $this->redirect('/');
        }
        $this->render('page_form');
    }
}