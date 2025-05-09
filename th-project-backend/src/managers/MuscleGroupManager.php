<?php
namespace App\Managers;

use App\Models\MuscleGroup;
use PDO;
use PDOException;
require_once __DIR__ . '/../models/MuscleGroup.php';

class MuscleGroupManager {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllMuscleGroups(): array {
        $sql = "SELECT * FROM muscle_groups";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMuscleGroupById(int $id): ?MuscleGroup {
        $sql = "SELECT * FROM muscle_groups WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new MuscleGroup($data) : null;
    }

    public function createMuscleGroup(MuscleGroup $muscleGroup): bool {
        $sql = "INSERT INTO muscle_groups (name) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['name' => $muscleGroup->getName()]);
    }

    public function updateMuscleGroup(MuscleGroup $muscleGroup): bool {
        $sql = "UPDATE muscle_groups SET name = :name WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $muscleGroup->getName(),
            'id' => $muscleGroup->getId(),
        ]);
    }

    public function deleteMuscleGroup(int $id): bool {
        $sql = "DELETE FROM muscle_groups WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function getMuscleGroupByName(string $name): ?MuscleGroup {
        $sql = "SELECT * FROM muscle_groups WHERE name = :name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new MuscleGroup($data) : null;
    }

    public function getMuscleGroupsByExerciseId(int $id): array {
        $sql = "SELECT muscle_groups.* FROM muscle_groups
                INNER JOIN exercise_muscle_groups ON muscle_groups.id = exercise_muscle_groups.muscle_group_id
                WHERE exercise_muscle_groups.exercise_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMuscleGroupToExercise(int $exerciseId, int $muscleGroupId): bool {
        $sql = "INSERT INTO exercise_muscle_groups (exercise_id, muscle_group_id) VALUES (:exercise_id, :muscle_group_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'exercise_id' => $exerciseId,
            'muscle_group_id' => $muscleGroupId,
        ]);
    }

    public function removeMuscleGroupFromExercise(int $exerciseId, int $muscleGroupId): bool {
        $sql = "DELETE FROM exercise_muscle_groups WHERE exercise_id = :exercise_id AND muscle_group_id = :muscle_group_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'exercise_id' => $exerciseId,
            'muscle_group_id' => $muscleGroupId,
        ]);
    }

    
}