<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../managers/TemplateManager.php';
require_once __DIR__ . '/../models/Template.php';

class TemplateController {
    private TemplateManager $templateManager;

    public function __construct() {
        global $pdo;
        $this->templateManager = new TemplateManager($pdo);
    }

    // Récupérer tous les templates
    public function getAllTemplates() {
        return $this->templateManager->getAllTemplates();
    }

    // Récupérer un template par ID
    public function getTemplate(int $id) {
        $template = $this->templateManager->getTemplateById($id);
        if (!$template) {
            http_response_code(404);
            return ["message" => "Template non trouvé"];
        }
        return $template;
    }

    // Créer un template
    public function createTemplate() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['name'], $data['description'], $data['user_id'])) {
            http_response_code(400);
            return ["message" => "Formulaire incomplet"];
        }

        $template = new Template($data);
        if ($this->templateManager->createTemplate($template)) {
            http_response_code(201);
            return ["message" => "Template créé avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la création"];
        }
    }

    // Mettre à jour un template
    public function updateTemplate(int $id) {
        $template = $this->templateManager->getTemplateById($id);
        if (!$template) {
            http_response_code(404);
            return ["message" => "Template non trouvé"];
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['name'], $data['description'])) {
            http_response_code(400);
            return ["message" => "Formulaire incomplet"];
        }

        $template->setName($data['name']);
        $template->setDescription($data['description']);
        if ($this->templateManager->updateTemplate($template)) {
            return ["message" => "Template mis à jour avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la mise à jour"];
        }
    }

    // Supprimer un template
    public function deleteTemplate(int $id) {
        if ($this->templateManager->deleteTemplate($id)) {
            http_response_code(200);
            return ["message" => "Template supprimé avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la suppression"];
        }
    }
}
