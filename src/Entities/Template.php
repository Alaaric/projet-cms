<?php

namespace App\Entities;

class Template {
    public function __construct(
        private string $name,
        private string $structure,
        private ?string $id = null,
        private ?string $createdAt = null
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

    public function getStructure(): string {
        return $this->structure;
    }

    public function setStructure(string $structure): void {
        $this->structure = $structure;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }
}