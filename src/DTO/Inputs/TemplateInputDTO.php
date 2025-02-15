<?php

namespace App\DTO\Inputs;

class TemplateInputDTO {
    public function __construct(
        private string $name,
        private string $structure
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function getStructure(): string {
        return $this->structure;
    }
}