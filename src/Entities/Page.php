<?php

namespace App\Entities;

use App\Repositories\PageRepository;

class Page {
    public function __construct(
        private string $title,
        private Header $header,
        private Body $body,
        private Footer $footer,
        private string $userId,
        private string $slug,
        private ?string $id = null,
        private ?string $createdAt = null
    ) {}

    public function getId(): ?string {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getHeader(): Header {
        return $this->header;
    }
    
    public function getBody(): Body {
        return $this->body;
    }

    public function getFooter(): Footer {
        return $this->footer;
    }

    public function getUserId(): string {
        return $this->userId;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
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
