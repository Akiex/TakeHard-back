<?php 
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../managers/MuscleGroupManager.php';
require_once __DIR__ . '/../models/MuscleGroup.php';
class MuscleGroupController { 
    private MuscleGroupManager $muscleGroupManager;

    public function __construct() {
        global $pdo;
        $this->muscleGroupManager = new MuscleGroupManager($pdo);
    }

    public function getAllMuscleGroups() {
        return $this->muscleGroupManager->getAllMuscleGroups();
    }

    public function getMuscleGroup(int $id) {
        $muscleGroup = $this->muscleGroupManager->getMuscleGroupById($id);
        if (!$muscleGroup) {
            http_response_code(404);
            return ["message" => "Groupe musculaire non trouvé"];
        }
        return $muscleGroup;
    }

    public function createMuscleGroup() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['name'], $data['description'])) {
            http_response_code(400);
            return ["message" => "Données incomplètes"];
        }

        if ($this->muscleGroupManager->createMuscleGroup($data)) {
            http_response_code(201);
            return ["message" => "Groupe musculaire créé avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la création"];
        }
    }

    public function updateMuscleGroup(int $id) {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->muscleGroupManager->updateMuscleGroup($id, $data)) {
            return ["message" => "Groupe musculaire mis à jour avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la mise à jour"];
        }
    }

    public function deleteMuscleGroup(int $id) {
        if ($this->muscleGroupManager->deleteMuscleGroup($id)) {
            return ["message" => "Groupe musculaire supprimé"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la suppression"];
        }
    }
    
}