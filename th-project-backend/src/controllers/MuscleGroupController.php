<?php 
namespace App\Controllers;

use App\Managers\MuscleGroupManager;
use App\Models\MuscleGroup;
use PDO;
use PDOException;

class MuscleGroupController { 
    private MuscleGroupManager $muscleGroupManager;

    public function __construct(PDO $pdo) {
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

        // 1) Récupérer l’objet existant
        $mg = $this->muscleGroupManager->getMuscleGroupById($id);
        if (!$mg) {
            http_response_code(404);
            return ["message" => "Groupe musculaire non trouvé"];
        }

        // 2) Hydrater partiellement
        if (isset($data['name'])) {
            $mg->setName($data['name']);
        }

        // 3) Appeler le Manager avec l’objet
        if ($this->muscleGroupManager->updateMuscleGroup($mg)) {
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