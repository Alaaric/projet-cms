<?php

namespace App\Repositories;

use App\Core\Database;
use App\Entities\Template;
use PDO;

class TemplateRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT * FROM templates");
        $templatesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($data) => new Template(
            $data['name'],
            $data['structure'],
            $data['id'],
            $data['created_at']
        ), $templatesData);
    }

    public function findById(string $id): ?Template {
        $stmt = $this->db->prepare("SELECT * FROM templates WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Template(
            $data['name'],
            $data['structure'],
            $data['id'],
            $data['created_at']
        ) : null;
    }

    public function save(Template $template): void {
        if ($template->getId()) {
            $stmt = $this->db->prepare("UPDATE templates SET name = ?, structure = ? WHERE id = ?");
            $stmt->execute([
                $template->getName(),
                $template->getStructure(),
                $template->getId()
            ]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO templates (name, structure) VALUES (?, ?)");
            $stmt->execute([
                $template->getName(),
                $template->getStructure()
            ]);
        }
    }

    public function delete(string $id): void {
        $stmt = $this->db->prepare("DELETE FROM templates WHERE id = ?");
        $stmt->execute([$id]);
    }
}