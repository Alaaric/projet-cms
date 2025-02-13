<?php

namespace App\Entities;

class Body {
    public function __construct(
        private string $content,
        private ?string $structure = null
    ) {}

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function getStructure(): ?string {
        return $this->structure;
    }

    public function setStructure(?string $structure): void {
        $this->structure = $structure;
    }
}
