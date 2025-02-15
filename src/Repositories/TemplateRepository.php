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

    public function findLatest(): Template {
        try {
            $stmt = $this->db->query("SELECT * FROM templates ORDER BY created_at DESC LIMIT 1");
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return new Template(
                $data['version'],
                $data['structure'],
                $data['id'],
                $data['created_at']
            );
        } catch (PDOException $e) {
            throw new TemplateRepositoryException("Erreur lors de la récupération du dernier template : " . $e->getMessage());
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
                $data['version'],
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
            $stmt = $this->db->prepare("SELECT version FROM templates WHERE id = ?");
            $stmt->execute([$id]);
            $currentVersion = $stmt->fetchColumn();
    
            $newVersion = $currentVersion + 1;
    
            $stmt = $this->db->prepare("INSERT INTO templates (version, structure ) VALUES (?, ?)");
            $stmt->execute([
                $newVersion,
                $templateInputDTO->getStructure()
            ]);
        } catch (PDOException $e) {
            throw new TemplateRepositoryException("Erreur lors de la mise à jour du template : " . $e->getMessage());
        }
    }
}