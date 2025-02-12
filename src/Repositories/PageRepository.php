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

        return array_map(fn($data) => new Page($data['id'], $data['title'], $data['content']), $pagesData);
    }

    public function findById(int $id): ?Page {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Page($data['id'], $data['title'], $data['content']) : null;
    }

    public function save(Page $page): void {
        if ($page->getId()) {
            $stmt = $this->db->prepare("UPDATE pages SET title = ?, content = ? WHERE id = ?");
            $stmt->execute([$page->getTitle(), $page->getContent(), $page->getId()]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO pages (title, content) VALUES (?, ?)");
            $stmt->execute([$page->getTitle(), $page->getContent()]);
        }
    }
}
