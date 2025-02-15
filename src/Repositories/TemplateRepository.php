<?php

namespace App\Repositories;

use App\Core\Database;
use App\Entities\Template;
use App\DTO\Inputs\TemplateInputDTO;
use App\Exceptions\Repositories\TemplateRepositoryException;
use PDO;
use PDOException;

class TemplateRepository {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findAll(): array {
        try {
            $stmt = $this->db->query("SELECT * FROM templates");
            $templatesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($data) => new Template(
                $data['name'],
                $data['structure'],
                $data['id'],
                $data['created_at']
            ), $templatesData);
        } catch (PDOException $e) {
            throw new TemplateRepositoryException("Erreur lors de la récupération des templates : " . $e->getMessage());
        }
    }

    public function findById(string $id): ?Template {
        try {
            $stmt = $this->db->prepare("SELECT * FROM templates WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                throw new TemplateRepositoryException("Template non trouvé avec l'ID : $id");
            }

            return new Template(
                $data['name'],
                $data['structure'],
                $data['id'],
                $data['created_at']
            );
        } catch (PDOException $e) {
            throw new TemplateRepositoryException("Erreur lors de la récupération du template : " . $e->getMessage());
        }
    }

    public function update(string $id, TemplateInputDTO $templateInputDTO): void {
        try {
            $stmt = $this->db->prepare("UPDATE templates SET name = ?, structure = ? WHERE id = ?");
            $stmt->execute([
                $templateInputDTO->getName(),
                $templateInputDTO->getStructure(),
                $id
            ]);
        } catch (PDOException $e) {
            throw new TemplateRepositoryException("Erreur lors de la mise à jour du template : " . $e->getMessage());
        }
    }
}