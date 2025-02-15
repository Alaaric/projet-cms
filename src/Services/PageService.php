<?php

namespace App\Services;

use App\DTO\Inputs\PageInputDTO;
use App\Entities\Page;
use App\Repositories\PageRepository;
use App\Repositories\TemplateRepository;
use App\Repositories\UserRepository;
use Exception;

class PageService {
    private PageRepository $pageRepo;
    private TemplateRepository $templateRepo;
    private UserRepository $userRepo;

    public function __construct(
        PageRepository $pageRepo,
        TemplateRepository $templateRepo,
        UserRepository $userRepo
    ) {
        $this->pageRepo = $pageRepo;
        $this->templateRepo = $templateRepo;
        $this->userRepo = $userRepo;
    }

    public function createPage(PageInputDTO $pageInputDTO): void {
        $slug = $pageInputDTO->getSlug();
        if ($this->pageRepo->slugExists($slug)) {
            throw new Exception('Le slug existe déjà. Veuillez en choisir un autre.');
        }

        $this->pageRepo->save($pageInputDTO);
    }

    public function updatePage(string $id, PageInputDTO $pageInputDTO): void {
        $slug = $pageInputDTO->getSlug();
        if ($this->pageRepo->slugExists($slug, $id)) {
            throw new Exception('Le slug existe déjà. Veuillez en choisir un autre.');
        }

        $this->pageRepo->update($id, $pageInputDTO);
    }

    public function findAll(): array {
        return $this->pageRepo->findAll();
    }

    public function findBySlug(string $slug): ?Page {
        return $this->pageRepo->findBySlug($slug);
    }

    public function deletePage(string $id): void {
        $this->pageRepo->delete($id);
    }

    public function reassignPages(string $oldUserId, string $newUserId): void {
        $this->pageRepo->reassignPages($oldUserId, $newUserId);
    }
}