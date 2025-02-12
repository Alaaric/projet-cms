<?php

namespace App\Entities;

class Page {
    public function __construct(
        private string $title,
        private string $content,
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

    public function getContent(): string {
        return $this->content;
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

}
