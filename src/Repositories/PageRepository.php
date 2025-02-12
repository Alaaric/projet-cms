<?php

namespace App\Repositories;

use App\Core\Database;
use App\Entities\Page;
use PDO;

class PageRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM pages");
        $pagesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($data) => new Page($data['title'], $data['content'], $data['user_id'], $data['slug'], $data['created_at']), $pagesData);
    }

    public function findBySlug(string $slug): ?Page {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Page(
            id: $data['id'],
            title: $data['title'],
            slug: $data['slug'],
            content: $data['content'],
            userId: $data['user_id'],
            createdAt: $data['created_at']
        ) : null;
    }

    public function findById(string $id): ?Page {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Page($data['title'], $data['content'], $data['user_id'], $data['slug'], $data['id'], $data['created_at']) : null;
    }

    public function save(Page $page): void {
        if ($page->getId()) {
            $stmt = $this->db->prepare("UPDATE pages SET title = ?, slug = ?, content = ? WHERE id = ?");
            $stmt->execute([$page->getTitle(), $page->getSlug(), $page->getContent(), $page->getId()]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO pages (title, slug, content, user_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$page->getTitle(), $page->getSlug(), $page->getContent(), $page->getUserId()]);
        }
    }
}
