<?php

namespace App\Entities;

use App\Repositories\PageRepository;

class Page {
    public function __construct(
        private string $name,
        private array $content,
        private string $userId,
        private string $templateId,
        private string $slug,
        private ?string $id = null,
        private ?string $createdAt = null,
        private ?string $updatedAt = null
    ) {}

    public function getId(): ?string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getContent(): array {
        return $this->content;
    }

    public function setContent(array $content): void {
        $this->content = $content;
    }

    public function getUserId(): string {
        return $this->userId;
    }

    public function getTemplateId(): string {
        return $this->templateId;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?string {
        return $this->updatedAt;
    }

    public function getSlug(): ?string {
        return $this->slug;
    }

    public function setSlug(string $slug, PageRepository $pageRepo): void {
        $baseSlug = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $slug), '-'));
        $slug = $baseSlug;
        $counter = 1;

        while ($pageRepo->slugExists($slug, $this->id)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $this->slug = $slug;
    }
}