<?php
namespace App\Controllers;

use App\Managers\ExerciseManager;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use App\Managers\MuscleGroupManager;
use PDO;
use App\Services\ValidationService;
use PDOException;

class ExerciseController {
    private ExerciseManager $exerciseManager;
    private ValidationService $validationService;
    public function __construct(PDO $pdo , ValidationService $validationService ) {
        $this->exerciseManager = new ExerciseManager($pdo);
        $this->validationService = $validationService;
    }

    // Récupérer tous les exercices
    public function getAllExercises(): array {
        return $this->exerciseManager->getAllExercises();
    }

    // Récupérer un exercice par ID
    public function getExercise(int $id) {
        $exercise = $this->exerciseManager->getExerciseById($id);
        if (!$exercise) {
            http_response_code(404);
            return ["message" => "Exercice non trouvé"];
        }
        return $exercise;
    }

    // Créer un nouvel exercice
    public function createExercise() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['name']) || empty($data['description']) || empty($data['muscleGroupId'])) {
            http_response_code(400);
            return ["message" => "Données incomplètes"];
        }

        // Instanciation de l'objet Exercise
        $exercise = new Exercise([
            'name'           => $data['name'],
            'description'    => $data['description'],
            'muscle_group_id'=> $data['muscleGroupId'],
        ]);

        if ($this->exerciseManager->createExercise($exercise)) {
            http_response_code(201);
            return ["message" => "Exercice créé avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la création"];
        }
    }

    // Mettre à jour un exercice existant
    public function updateExercise(int $id) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Récupération de l'exercice existant
        $exercise = $this->exerciseManager->getExerciseById($id);
        if (!$exercise) {
            http_response_code(404);
            return ["message" => "Exercice non trouvé"];
        }

        // Hydratation avec les nouvelles données (si présentes)
        $exercise->setName($data['name']        ?? $exercise->getName());
        $exercise->setDescription($data['description'] ?? $exercise->getDescription());
        $exercise->setMuscleGroupId($data['muscleGroupId'] ?? $exercise->getMuscleGroupId());

        if ($this->exerciseManager->updateExercise($exercise)) {
            return ["message" => "Exercice mis à jour avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la mise à jour"];
        }
    }

    // Supprimer un exercice
    public function deleteExercise(int $id) {
        // Vérifier l'existence
        if (!$this->exerciseManager->getExerciseById($id)) {
            http_response_code(404);
            return ["message" => "Exercice non trouvé"];
        }

        if ($this->exerciseManager->deleteExercise($id)) {
            return ["message" => "Exercice supprimé"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la suppression"];
        }
    }
}
