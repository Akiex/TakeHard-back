<?php
namespace App\Managers;
use App\Models\Exercise;
use PDO;

class ExerciseManager
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllExercises(): array
    {
        $sql = "SELECT * FROM exercises";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExerciseById(int $id): ?Exercise
    {
        $sql = "SELECT * FROM exercises WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Exercise($data) : null;
    }

    public function createExercise(Exercise $exercise): bool
    {
        $sql = "INSERT INTO exercises (name, description) VALUES (:name, :description)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $exercise->getName(),
            'description' => $exercise->getDescription(),
        ]);
    }

    public function updateExercise(Exercise $exercise): bool
    {
        $sql = "UPDATE exercises SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'name' => $exercise->getName(),
            'description' => $exercise->getDescription(),
            'id' => $exercise->getId(),
        ]);
    }

    public function deleteExercise(int $id): bool
    {
        $sql = "DELETE FROM exercises WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function getExercisesByMuscleGroupId(int $id): array
    {
        $sql = "SELECT * FROM exercises WHERE id IN (SELECT exercise_id FROM exercise_muscle_groups WHERE muscle_group_id = :id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExercisesByTemplateId(int $id): array
    {
        $sql = "SELECT * FROM exercises WHERE id IN (SELECT exercise_id FROM exercise_templates WHERE template_id = :id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExercisesByWorkoutId(int $id): array
    {
        $sql = "SELECT * FROM exercises WHERE id IN (SELECT exercise_id FROM exercise_workouts WHERE workout_id = :id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExercisesByUserId(int $id): array
    {
        $sql = "SELECT * FROM exercises WHERE id IN (SELECT exercise_id FROM exercise_users WHERE user_id = :id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExercisesByUserIdAndTemplateId(int $userId, int $templateId): array
    {
        $sql = "SELECT exercises.* FROM exercises
                INNER JOIN exercise_users ON exercises.id = exercise_users.exercise_id
                INNER JOIN exercise_templates ON exercises.id = exercise_templates.exercise_id
                WHERE exercise_users.user_id = :userId AND exercise_templates.template_id = :templateId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId, 'templateId' => $templateId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExercisesByUserIdAndWorkoutId(int $userId, int $workoutId): array
    {
        $sql = "SELECT exercises.* FROM exercises
                INNER JOIN exercise_users ON exercises.id = exercise_users.exercise_id
                INNER JOIN exercise_workouts ON exercises.id = exercise_workouts.exercise_id
                WHERE exercise_users.user_id = :userId AND exercise_workouts.workout_id = :workoutId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId, 'workoutId' => $workoutId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExercisesByUserIdAndMuscleGroupId(int $userId, int $muscleGroupId): array
    {
        $sql = "SELECT exercises.* FROM exercises
                INNER JOIN exercise_users ON exercises.id = exercise_users.exercise_id
                INNER JOIN exercise_muscle_groups ON exercises.id = exercise_muscle_groups.exercise_id
                WHERE exercise_users.user_id = :userId AND exercise_muscle_groups.muscle_group_id = :muscleGroupId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId, 'muscleGroupId' => $muscleGroupId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExercisesByUserIdAndMuscleGroupIdAndTemplateId(int $userId, int $muscleGroupId, int $templateId): array
    {
        $sql = "SELECT exercises.* FROM exercises
                INNER JOIN exercise_users ON exercises.id = exercise_users.exercise_id
                INNER JOIN exercise_muscle_groups ON exercises.id = exercise_muscle_groups.exercise_id
                INNER JOIN exercise_templates ON exercises.id = exercise_templates.exercise_id
                WHERE exercise_users.user_id = :userId AND exercise_muscle_groups.muscle_group_id = :muscleGroupId AND exercise_templates.template_id = :templateId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['userId' => $userId, 'muscleGroupId' => $muscleGroupId, 'templateId' => $templateId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
