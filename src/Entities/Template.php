<?php

namespace App\Entities;

class Template {
    public function __construct(
        private int $version,
        private string $structure,
        private ?string $id = null,
        private ?string $createdAt = null
    ) {}

    public function getId(): ?string {
        return $this->id;
    }

    public function getVersion(): int {
        return $this->version;
    }

    public function getStructure(): string {
        return $this->structure;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }
}