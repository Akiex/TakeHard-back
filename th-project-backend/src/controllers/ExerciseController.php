<?php
namespace App\Controllers;

use App\Managers\ExerciseManager;
use App\Models\Exercise;
use App\Models\MuscleGroup;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ExerciseController {
    private ExerciseManager $exerciseManager;

    public function __construct(ExerciseManager $exerciseManager) {
        $this->exerciseManager = $exerciseManager;
    }

    public function getAllExercises(Request $request, Response $response): Response {
        $exercises = $this->exerciseManager->getAllExercises();

        $payload = array_map(function(Exercise $ex) {
            return [
                'id'            => $ex->getId(),
                'name'          => $ex->getName(),
                'description'   => $ex->getDescription(),
                'muscle_groups' => array_map(function(MuscleGroup $mg) {
                    return [
                        'id'   => $mg->getId(),
                        'name' => $mg->getName(),
                    ];
                }, $ex->getMuscleGroups()),
            ];
        }, $exercises);

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getExercise(Request $request, Response $response, array $args): Response {
        $exercise = $this->exerciseManager->getExerciseById((int)$args['id']);
        if (!$exercise) {
            $response->getBody()->write(json_encode(['message' => 'Exercice non trouvé']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $data = [
            'id'            => $exercise->getId(),
            'name'          => $exercise->getName(),
            'description'   => $exercise->getDescription(),
            'muscle_groups' => array_map(fn(MuscleGroup $mg) => [
                'id'   => $mg->getId(),
                'name' => $mg->getName()
            ], $exercise->getMuscleGroups()),
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createExercise(Request $request, Response $response): Response {
        $data = (array)$request->getParsedBody();
        $exercise = $this->exerciseManager->createExercise($data);

        $payload = [
            'id'            => $exercise->getId(),
            'name'          => $exercise->getName(),
            'description'   => $exercise->getDescription(),
            'muscle_groups' => array_map(fn(MuscleGroup $mg) => [
                'id' => $mg->getId(),
                'name' => $mg->getName(),
            ], $exercise->getMuscleGroups()),
        ];

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function updateExercise(Request $request, Response $response, array $args): Response {
        $id = (int)$args['id'];
        $data = (array)$request->getParsedBody();

        $exercise = $this->exerciseManager->updateExercise($id, $data);
        if (!$exercise) {
            $response->getBody()->write(json_encode(['message' => 'Exercice non trouvé']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $payload = [
            'id'            => $exercise->getId(),
            'name'          => $exercise->getName(),
            'description'   => $exercise->getDescription(),
            'muscle_groups' => array_map(fn(MuscleGroup $mg) => [
                'id' => $mg->getId(),
                'name' => $mg->getName(),
            ], $exercise->getMuscleGroups()),
        ];

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteExercise(Request $request, Response $response, array $args): Response {
        $id = (int)$args['id'];
        $success = $this->exerciseManager->deleteExercise($id);

        if (!$success) {
            $response->getBody()->write(json_encode(['message' => 'Erreur lors de la suppression']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        $response->getBody()->write(json_encode(['message' => 'Exercice supprimé avec succès']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
