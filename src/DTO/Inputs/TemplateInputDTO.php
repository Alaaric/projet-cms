<?php

namespace App\DTO\Inputs;

class TemplateInputDTO {
    public function __construct(
        private string $structure
    ) {}

    public function getStructure(): string {
        return $this->structure;
    }
}