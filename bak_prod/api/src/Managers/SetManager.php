<?php
namespace App\Managers;

use App\Models\Set;
use App\Models\Exercise;
use PDO;
use PDOException;

class SetManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupérer tous les sets avec leurs exercises
    public function getAllSets(): array
    {
        $sql = "
            SELECT
                s.id        AS set_id,
                s.weight    AS weight,
                s.sets      AS sets_count,
                s.reps      AS reps,
                s.rest_time AS rest_time,
                e.id        AS exercise_id,
                e.name      AS exercise_name
            FROM sets s
            LEFT JOIN sets_exercise se ON s.id = se.set_id
            LEFT JOIN exercises e       ON se.exercise_id = e.id
        ";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sets = [];
        foreach ($rows as $row) {
            $sid = (int)$row['set_id'];
            if (!isset($sets[$sid])) {
                $sets[$sid] = new Set([
                    'id'        => $sid,
                    'weight'    => $row['weight'],
                    'sets'      => $row['sets_count'],
                    'reps'      => $row['reps'],
                    'rest_time' => $row['rest_time'],
                ]);
            }

            if ($row['exercise_id'] !== null) {
                $sets[$sid]->addExercise(new Exercise([
                    'id'   => (int)$row['exercise_id'],
                    'name' => $row['exercise_name'],
                ]));
            }
        }

        return array_values($sets);
    }

    // Récupérer un set par ID
    public function getSetById(int $id): ?Set
    {
        $sql = "SELECT * FROM sets WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        $set = new Set($data);

        // récupérer les exercises liés
        $sql = "
            SELECT e.id, e.name
            FROM exercises e
            INNER JOIN sets_exercise se ON e.id = se.exercise_id
            WHERE se.set_id = :id
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $exs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($exs as $exData) {
            $set->addExercise(new Exercise($exData));
        }

        return $set;
    }

    // Créer un nouveau set
    public function createSet(array $data): Set
    {
        $sql = "INSERT INTO sets (weight, sets, reps, rest_time) VALUES (:weight, :sets, :reps, :rest_time)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'weight'    => $data['weight'],
            'sets'      => $data['sets'],
            'reps'      => $data['reps'],
            'rest_time' => $data['rest_time'],
        ]);
        $setId = (int)$this->pdo->lastInsertId();

        if (!empty($data['exercise_ids']) && is_array($data['exercise_ids'])) {
            foreach ($data['exercise_ids'] as $exId) {
                $stmt = $this->pdo->prepare("INSERT INTO sets_exercise (set_id, exercise_id) VALUES (:sid, :eid)");
                $stmt->execute(['sid' => $setId, 'eid' => $exId]);
            }
        }

        return $this->getSetById($setId);
    }

    // Mettre à jour un set existant
    public function updateSet(int $id, array $data): ?Set
    {
        $sql = "UPDATE sets SET weight = :weight, sets = :sets, reps = :reps, rest_time = :rest_time WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'weight'    => $data['weight'],
            'sets'      => $data['sets'],
            'reps'      => $data['reps'],
            'rest_time' => $data['rest_time'],
            'id'        => $id,
        ]);

        // Réassocier exercises
        $stmt = $this->pdo->prepare("DELETE FROM sets_exercise WHERE set_id = :id");
        $stmt->execute(['id' => $id]);
        if (!empty($data['exercise_ids']) && is_array($data['exercise_ids'])) {
            foreach ($data['exercise_ids'] as $exId) {
                $stmt = $this->pdo->prepare("INSERT INTO sets_exercise (set_id, exercise_id) VALUES (:sid, :eid)");
                $stmt->execute(['sid' => $id, 'eid' => $exId]);
            }
        }

        return $this->getSetById($id);
    }

    // Supprimer un set
    public function deleteSet(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM sets_exercise WHERE set_id = :id");
        $stmt->execute(['id' => $id]);

        $stmt = $this->pdo->prepare("DELETE FROM sets WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}