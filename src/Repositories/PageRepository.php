<?php

namespace App\Repositories;

use App\Core\Database;
use App\Entities\Page;
use App\Entities\Header;
use App\Entities\Body;
use App\Entities\Footer;

use PDO;

class PageRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM pages");
        $pagesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($data) => new Page(
            $data['title'],
             new Header($data['header']),
              new Body($data['body']),
               new Footer($data['footer']),
                $data['user_id'],
                 $data['slug'],
                  $data['created_at']), $pagesData);
    }

    public function findBySlug(string $slug): ?Page {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Page(
            $data['title'],
             new Header($data['header']),
              new Body($data['body']),
               new Footer($data['footer']),
                $data['user_id'],
                 $data['slug'],
                  $data['created_at']) : null;
    }

    public function findById(string $id): ?Page {
        $stmt = $this->db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Page($data['title'], $data['content'], $data['user_id'], $data['slug'], $data['id'], $data['created_at']) : null;
    }

    public function save(Page $page): void {
        if ($page->getId()) {
            $stmt = $this->db->prepare("UPDATE pages SET title = ?, slug = ?, header = ?, body = ?, footer = ? WHERE id = ?");
            $stmt->execute([
                $page->getTitle(), 
                $page->getSlug(), 
                $page->getHeader()->getContent(), 
                $page->getBody()->getContent(), 
                $page->getFooter()->getContent(), 
                $page->getId()
            ]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO pages (title, slug, header, body, footer, user_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $page->getTitle(), 
                $page->getSlug(), 
                $page->getHeader()->getContent(), 
                $page->getBody()->getContent(), 
                $page->getFooter()->getContent(), 
                $page->getUserId()
            ]);
        }
    }

    public function delete(int $id): void {
        $stmt = $this->db->prepare("DELETE FROM pages WHERE id = ?");
        $stmt->execute([$id]);
    }
    
    public function reassignPages(int $oldUserId, int $newUserId): void {
        $stmt = $this->db->prepare("UPDATE pages SET user_id = ? WHERE user_id = ?");
        $stmt->execute([$newUserId, $oldUserId]);
    }

    public function slugExists(string $slug, ?string $currentPageId = null): bool {
        $query = "SELECT COUNT(*) FROM pages WHERE slug = ?";
        $params = [$slug];
    
        if ($currentPageId !== null) {
            $query .= " AND id != ?";
            $params[] = $currentPageId;
        }
    
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
    
        return $stmt->fetchColumn() > 0;
    }
    
}
