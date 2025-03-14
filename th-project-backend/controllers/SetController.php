<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../managers/SetManager.php';
require_once __DIR__ . '/../models/Set.php';
class SetController {
    private SetManager $setManager;

    public function __construct() {
        global $pdo;
        $this->setManager = new SetManager($pdo);
    }
    public function getAllSets() {
        return $this->setManager->getAllSets();
    }

    public function getSet(int $id) {
        $set = $this->setManager->getSetById($id);
        if (!$set) {
            http_response_code(404);
            return ["message" => "Entrainement non trouvé"];
        }
        return $set;
    }

    public function createSet() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['name'], $data['description'])) {
            http_response_code(400);
            return ["message" => "Données incomplètes"];
        }

        if ($this->setManager->createSet($data)) {
            http_response_code(201);
            return ["message" => "Entrainement créé avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la création"];
        }
    }

    public function updateSet(int $id) {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->setManager->updateSet($id, $data)) {
            return ["message" => "Entrainement mis à jour avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la mise à jour"];
        }
    }

    public function deleteSet(int $id) {
        if ($this->setManager->deleteSet($id)) {
            return ["message" => "Entrainement supprimé"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la suppression"];
        }
    }
    
}