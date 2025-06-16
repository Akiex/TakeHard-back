<?php
namespace App\Managers;

use App\Models\Template;
use App\Models\Set;
use App\Models\Exercise;
use PDO;

class TemplateManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Récupérer tous les templates avec leurs sets et exercices
    public function getAllTemplates(): array
    {
        $sql = "
            SELECT
                t.id            AS template_id,
                t.user_id,
                t.name          AS template_name,
                t.description   AS template_description,
                t.is_public,
                s.id            AS set_id,
                s.sets          AS sets_count,
                s.reps,
                s.weight,
                s.rest_time,
                e.id            AS exercise_id,
                e.name          AS exercise_name
            FROM templates t
            LEFT JOIN template_sets ts ON t.id = ts.template_id
            LEFT JOIN sets s           ON ts.set_id = s.id
            LEFT JOIN sets_exercise se ON s.id = se.set_id
            LEFT JOIN exercises e      ON se.exercise_id = e.id
            ORDER BY t.id, s.id, se.exercise_id
        ";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $templates = [];
        foreach ($rows as $row) {
            $tid = (int)$row['template_id'];
            if (!isset($templates[$tid])) {
                $templates[$tid] = new Template([
                    'id'          => $tid,
                    'user_id'     => $row['user_id'],
                    'name'        => $row['template_name'],
                    'description' => $row['template_description'],
                    'is_public'   => (bool)$row['is_public'],
                ]);
            }

            // Gérer les sets
            if ($row['set_id'] !== null) {
                $template = $templates[$tid];
                // Si le set n'existe pas encore dans ce template
                if (!isset($template->getSets()[$row['set_id']])) {
                    $template->addSet(new Set([
                        'id'        => (int)$row['set_id'],
                        'sets'      => $row['sets_count'],
                        'reps'      => $row['reps'],
                        'weight'    => $row['weight'],
                        'rest_time' => $row['rest_time'],
                    ]));
                }

                // Gérer les exercices du set
                if ($row['exercise_id'] !== null) {
                    $sets = $template->getSets();
                    /** @var Set $lastSet */
                    $lastSet = end($sets);
                    $lastSet->addExercise(new Exercise([
                        'id'   => (int)$row['exercise_id'],
                        'name' => $row['exercise_name'],
                    ]));
                }
            }
        }

        return array_values($templates);
    }

    public function getTemplateById(int $id): ?Template
    {
        $templates = $this->getAllTemplates();
        foreach ($templates as $template) {
            if ($template->getId() === $id) {
                return $template;
            }
        }
        return null;
    }

    public function createTemplate(array $data): Template
    {
        // 1. Créer le template
        $stmt = $this->pdo->prepare(
            "INSERT INTO templates (user_id, name, description, is_public)
             VALUES (:uid, :name, :desc, :pub)"
        );
        $stmt->execute([
            'uid'  => $data['user_id'],
            'name' => $data['name'],
            'desc' => $data['description'] ?? null,
            'pub'  => $data['is_public'] ?? false,
        ]);
        $tid = (int)$this->pdo->lastInsertId();

        // 2. Lier les sets et leurs exercices
        if (!empty($data['sets']) && is_array($data['sets'])) {
            foreach ($data['sets'] as $setData) {
                // Insert set if besoin, sinon simplement lier
                $stmt = $this->pdo->prepare(
                    "INSERT INTO template_sets (template_id, set_id)
                     VALUES (:tid, :sid)"
                );
                $stmt->execute([
                    'tid' => $tid,
                    'sid' => $setData['id'],
                ]);

                // Lier les exercices du set
                if (!empty($setData['exercises'])) {
                    foreach ($setData['exercises'] as $ex) {
                        $stmt = $this->pdo->prepare(
                            "INSERT INTO sets_exercise (set_id, exercise_id)
                             VALUES (:sid, :eid)"
                        );
                        $stmt->execute([
                            'sid' => $setData['id'],
                            'eid' => $ex['id'],
                        ]);
                    }
                }
            }
        }

        return $this->getTemplateById($tid);
    }

    public function updateTemplate(int $id, array $data): ?Template
    {
        // Mise à jour du template
        $stmt = $this->pdo->prepare(
            "UPDATE templates
             SET name = :name, description = :desc, is_public = :pub
             WHERE id = :id"
        );
        $stmt->execute([
            'name' => $data['name'],
            'desc' => $data['description'] ?? null,
            'pub'  => $data['is_public'] ?? false,
            'id'   => $id,
        ]);

        // Supprimer anciennes liaisons
        $this->pdo->prepare("DELETE FROM template_sets WHERE template_id = :id")
                  ->execute(['id' => $id]);

        if (!empty($data['sets'])) {
            foreach ($data['sets'] as $setData) {
                $stmt = $this->pdo->prepare(
                    "INSERT INTO template_sets (template_id, set_id)
                     VALUES (:tid, :sid)"
                );
                $stmt->execute(['tid' => $id, 'sid' => $setData['id']]);

                // Réassocier exercices
                $this->pdo->prepare("DELETE FROM sets_exercise WHERE set_id = :sid")
                          ->execute(['sid' => $setData['id']]);
                if (!empty($setData['exercises'])) {
                    foreach ($setData['exercises'] as $ex) {
                        $stmt = $this->pdo->prepare(
                            "INSERT INTO sets_exercise (set_id, exercise_id)
                             VALUES (:sid, :eid)"
                        );
                        $stmt->execute(['sid' => $setData['id'], 'eid' => $ex['id']]);
                    }
                }
            }
        }

        return $this->getTemplateById($id);
    }
    public function getTemplateByUserId(int $userId): array
    {
        return array_values(array_filter(
            $this->getAllTemplates(),
            fn(Template $t) => $t->getUserId() === $userId
        ));
    }
    public function deleteTemplate(int $id): bool
    {
        $this->pdo->prepare("DELETE FROM template_sets WHERE template_id = :id")
                  ->execute(['id' => $id]);
        return $this->pdo->prepare("DELETE FROM templates WHERE id = :id")
                         ->execute(['id' => $id]);
    }
}
