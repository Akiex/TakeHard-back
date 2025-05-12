<?php
namespace App\Managers;

use App\Models\Template;
use PDO;

class TemplateManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllTemplates(): array
    {
        $sql = "SELECT id, name, description FROM templates";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTemplateById(int $id): ?Template
    {
        $sql = "SELECT * FROM templates WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Template($data) : null;
    }

    public function createTemplate(Template $template): bool
    {
        $sql = "INSERT INTO templates (name, description) VALUES (:name, :description)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $template->getName(),
            'description' => $template->getDescription(),
        ]);
    }

    public function updateTemplate(Template $template): bool
    {
        $sql = "UPDATE templates SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $template->getName(),
            'description' => $template->getDescription(),
            'id' => $template->getId(),
        ]);
    }

    public function deleteTemplate(int $id): bool
    {
        $sql = "DELETE FROM templates WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
