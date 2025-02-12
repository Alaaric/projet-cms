<?php

namespace App\Entities;

class Page {
    public function __construct(
        private ?int $id = null,
        private string $title = "",
        private string $content = ""
    ) {}

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }
}
