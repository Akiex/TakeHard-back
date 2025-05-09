<?php
namespace App\Managers;

use App\Models\Set;
use PDO;
use PDOException;
require_once __DIR__ . '/../models/Set.php';

class SetManager
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;  
    }

    public function createSet(Set $set): bool
    {
        $sql = "INSERT INTO sets (weight, sets, reps, rest_time, exercise_id) VALUES (:weight, :sets, :reps, :rest_time, :exercise_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'sets' => $set->getSets(),
            'rest_time' => $set->getRestTime(),
            'weight' => $set->getWeight(),
            'reps' => $set->getReps(),
        ]);
    }

    public function getSetsByExerciseId(int $id): array
    {
        $sql = "SELECT * FROM sets WHERE exercise_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteSet(int $id): bool
    {
        $sql = "DELETE FROM sets WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function updateSet(Set $set): bool
    {
        $sql = "UPDATE sets SET weight = :weight, reps = :reps, sets = :sets, rest_time = :rest_time WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'sets' => $set->getSets(),
            'rest_time' => $set->getRestTime(),
            'weight' => $set->getWeight(),
            'reps' => $set->getReps(),
            'id' => $set->getId(),
        ]);
    }

    public function getSetById(int $id): ?Set
    {
        $sql = "SELECT * FROM sets WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Set($data) : null;
    }

    public function getAllSets(): array
    {
        $sql = "SELECT * FROM sets";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }   

    
}