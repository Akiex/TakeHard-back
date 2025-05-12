<?php
namespace App\Controllers;

use App\Managers\ExerciseManager;
use App\Models\Exercise;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ExerciseController
{
    private ExerciseManager $exerciseManager;

    public function __construct(ExerciseManager $exerciseManager)
    {
        $this->exerciseManager = $exerciseManager;
    }

    // Récupérer tous les exercices
    public function getAllExercises(Request $request, Response $response): Response
    {
        $exercises = $this->exerciseManager->getAllExercises();
        $response->getBody()->write(json_encode($exercises));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Récupérer un exercice par ID
    public function getExercise(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $exercise = $this->exerciseManager->getExerciseById($id);
        if (!$exercise) {
            $payload = json_encode(['message' => 'Exercice non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        $response->getBody()->write(json_encode($exercise));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Créer un nouvel exercice
    public function createExercise(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        if (empty($data['name']) || empty($data['description'])) {
            $payload = json_encode(['message' => 'Champs requis manquants']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $exercise = new Exercise($data);
        $this->exerciseManager->createExercise($exercise);

        $payload = json_encode(['message' => 'Exercice créé avec succès']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // Mettre à jour un exercice existant
    public function updateExercise(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $existingExercise = $this->exerciseManager->getExerciseById($id);
        if (!$existingExercise) {
            $payload = json_encode(['message' => 'Exercice non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $data = (array)$request->getParsedBody();
        $existingExercise->setName($data['name'] ?? $existingExercise->getName());
        $existingExercise->setDescription($data['description'] ?? $existingExercise->getDescription());

        $this->exerciseManager->updateExercise($existingExercise);

        $payload = json_encode(['message' => 'Exercice mis à jour']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Supprimer un exercice
    public function deleteExercise(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $existingExercise = $this->exerciseManager->getExerciseById($id);
        if (!$existingExercise) {
            $payload = json_encode(['message' => 'Exercice non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $this->exerciseManager->deleteExercise($id);
        $payload = json_encode(['message' => 'Exercice supprimé avec succès']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

