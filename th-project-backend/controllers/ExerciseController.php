<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../managers/ExerciseManager.php';
require_once __DIR__ . '/../models/Exercise.php';
class ExerciseController {
    private ExerciseManager $exerciseManager;

    public function __construct() {
        global $pdo;
        $this->exerciseManager = new ExerciseManager($pdo);
    }

    public function getAllExercises() {
        return $this->exerciseManager->getAllExercises();
    }

    public function getExercise(int $id) {
        $exercise = $this->exerciseManager->getExerciseById($id);
        if (!$exercise) {
            http_response_code(404);
            return ["message" => "Exercice non trouvé"];
        }
        return $exercise;
    }

    public function createExercise() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['name'], $data['description'], $data['muscleGroupId'])) {
            http_response_code(400);
            return ["message" => "Données incomplètes"];
        }

        if ($this->exerciseManager->createExercise($data)) {
            http_response_code(201);
            return ["message" => "Exercice créé avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la création"];
        }
    }

    public function updateExercise(int $id) {
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->exerciseManager->updateExercise($id, $data)) {
            return ["message" => "Exercice mis à jour avec succès"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la mise à jour"];
        }
    }

    public function deleteExercise(int $id) {
        if ($this->exerciseManager->deleteExercise($id)) {
            return ["message" => "Exercice supprimé"];
        } else {
            http_response_code(500);
            return ["message" => "Erreur lors de la suppression"];
        }
    }
}
