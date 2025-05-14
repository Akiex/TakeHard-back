<?php
namespace App\Managers;

use App\Models\Exercise;
use App\Models\MuscleGroup;
use PDO;

class ExerciseManager {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllExercises(): array {
        $sql = "
            SELECT 
                e.id   AS exercise_id,
                e.name AS exercise_name,
                e.description AS exercise_description,
                mg.id  AS mg_id,
                mg.name AS mg_name
            FROM exercises e
            LEFT JOIN exercise_muscle_groups emg 
                ON e.id = emg.exercise_id
            LEFT JOIN muscle_groups mg 
                ON emg.muscle_group_id = mg.id
        ";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $exercises = [];
        foreach ($rows as $row) {
            $eid = (int)$row['exercise_id'];

            if (!isset($exercises[$eid])) {
                $exercises[$eid] = new Exercise([
                    'id'          => $eid,
                    'name'        => $row['exercise_name'],
                    'description' => $row['exercise_description'],
                ]);
            }

            if ($row['mg_id'] !== null) {
                $exercises[$eid]->addMuscleGroup(new MuscleGroup([
                    'id'   => (int)$row['mg_id'],
                    'name' => $row['mg_name'],
                ]));
            }
        }

        return array_values($exercises);
    }

    public function getExerciseById(int $id): ?Exercise {
        $sql = "SELECT * FROM exercises WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        $exercise = new Exercise($data);

        $sql = "
            SELECT mg.id, mg.name
            FROM muscle_groups mg
            INNER JOIN exercise_muscle_groups emg ON mg.id = emg.muscle_group_id
            WHERE emg.exercise_id = :id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($groups as $g) {
            $exercise->addMuscleGroup(new MuscleGroup($g));
        }

        return $exercise;
    }

    public function createExercise(array $data): Exercise {
        $stmt = $this->pdo->prepare("INSERT INTO exercises (name, description) VALUES (:name, :description)");
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
        $exerciseId = (int)$this->pdo->lastInsertId();

        if (!empty($data['muscle_group_ids']) && is_array($data['muscle_group_ids'])) {
            foreach ($data['muscle_group_ids'] as $mgId) {
                $stmt = $this->pdo->prepare("INSERT INTO exercise_muscle_groups (exercise_id, muscle_group_id) VALUES (:eid, :mgid)");
                $stmt->execute(['eid' => $exerciseId, 'mgid' => $mgId]);
            }
        }

        return $this->getExerciseById($exerciseId);
    }

    public function updateExercise(int $id, array $data): ?Exercise {
        $stmt = $this->pdo->prepare("UPDATE exercises SET name = :name, description = :description WHERE id = :id");
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'id' => $id,
        ]);

        $stmt = $this->pdo->prepare("DELETE FROM exercise_muscle_groups WHERE exercise_id = :id");
        $stmt->execute(['id' => $id]);

        if (!empty($data['muscle_group_ids']) && is_array($data['muscle_group_ids'])) {
            foreach ($data['muscle_group_ids'] as $mgId) {
                $stmt = $this->pdo->prepare("INSERT INTO exercise_muscle_groups (exercise_id, muscle_group_id) VALUES (:eid, :mgid)");
                $stmt->execute(['eid' => $id, 'mgid' => $mgId]);
            }
        }

        return $this->getExerciseById($id);
    }

    public function deleteExercise(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM exercise_muscle_groups WHERE exercise_id = :id");
        $stmt->execute(['id' => $id]);

        $stmt = $this->pdo->prepare("DELETE FROM exercises WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}