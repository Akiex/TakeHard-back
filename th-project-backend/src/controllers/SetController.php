<?php
namespace App\Controllers;

use App\Managers\SetManager;
use App\Models\Set;
use PDO;
use PDOException;
class SetController {
    private SetManager $setManager;

    public function __construct(PDO $pdo) {
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
        $existing = $this->setManager->getSetById($id);
        if (!$existing) { /* 404… */ }
        // Hydrate l’objet Set existant
        $existing->setWeight($data['weight'] ?? $existing->getWeight());
        $existing->setReps($data['reps']     ?? $existing->getReps());
        $existing->setSets($data['sets']     ?? $existing->getSets());
        $existing->setRestTime($data['rest_time'] ?? $existing->getRestTime());
        if ($this->setManager->updateSet($existing)) { /* succès */ }
        else { /* erreur 500 */ }
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