<?php

namespace App\DTO\Inputs;

class PageInputDTO {
    public function __construct(
        private string $name,
        private string $slug,
        private array $content,
        private string $userId,
        private string $templateId
    ) {}

    public function getName(): string {
        return $this->name;
    }

    public function getSlug(): string {
        return $this->slug;
    }

    public function getContent(): array {
        return $this->content;
    }

    public function getUserId(): string {
        return $this->userId;
    }

    public function getTemplateId(): string {
        return $this->templateId;
    }
}