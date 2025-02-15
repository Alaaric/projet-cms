<?php

namespace App\Repositories;

use App\Core\Database;
use App\Entities\Page;
use App\Exceptions\Repositories\PageRepositoryException;
use PDO;
use PDOException;

class PageRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM pages");
            $pagesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($data) => new Page(
                $data['name'],
                json_decode($data['content'], true) ?? [],
                $data['user_id'],
                $data['template_id'],
                $data['slug'],
                $data['id'],
                $data['created_at'],
                $data['updated_at']
            ), $pagesData);
        } catch (PDOException $e) {
            throw new PageRepositoryException("Erreur lors de la récupération des pages : " . $e->getMessage());
        }
    }

    public function findBySlug(string $slug): ?Page {
        try {
            $stmt = $this->db->prepare("SELECT * FROM pages WHERE slug = ?");
            $stmt->execute([$slug]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new PageRepositoryException("Page non trouvée avec le slug : $slug");
            }

            return new Page(
                $data['name'],
                json_decode($data['content'], true) ?? [],
                $data['user_id'],
                $data['template_id'],
                $data['slug'],
                $data['id'],
                $data['created_at'],
                $data['updated_at']
            );
        } catch (PDOException $e) {
            throw new PageRepositoryException("Erreur lors de la récupération de la page : " . $e->getMessage());
        }
    }

    public function save(Page $page): void {
        try {
            if ($page->getId()) {
                $stmt = $this->db->prepare("UPDATE pages SET name = ?, slug = ?, content = ?, template_id = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
                $stmt->execute([
                    $page->getName(),
                    $page->getSlug(),
                    json_encode($page->getContent()),
                    $page->getTemplateId(),
                    $page->getId()
                ]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO pages (name, slug, content, user_id, template_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $page->getName(),
                    $page->getSlug(),
                    json_encode($page->getContent()),
                    $page->getUserId(),
                    $page->getTemplateId()
                ]);
            }
        } catch (PDOException $e) {
            throw new PageRepositoryException("Erreur lors de l'enregistrement de la page : " . $e->getMessage());
        }
    }

    public function delete(string $id): void {
        try {
            $stmt = $this->db->prepare("DELETE FROM pages WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new PageRepositoryException("Erreur lors de la suppression de la page : " . $e->getMessage());
        }
    }

    public function reassignPages(string $oldUserId, string $newUserId): void {
        try {
            $stmt = $this->db->prepare("UPDATE pages SET user_id = ? WHERE user_id = ?");
            $stmt->execute([$newUserId, $oldUserId]);
        } catch (PDOException $e) {
            throw new PageRepositoryException("Erreur lors de la réaffectation des pages : " . $e->getMessage());
        }
    }

    public function slugExists(string $slug, ?string $currentPageId = null): bool {
        try {
            $query = "SELECT COUNT(*) FROM pages WHERE slug = ?";
            $params = [$slug];

            if ($currentPageId !== null) {
                $query .= " AND id != ?";
                $params[] = $currentPageId;
            }

            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            throw new PageRepositoryException("Erreur lors de la vérification du slug : " . $e->getMessage());
        }
    }
}