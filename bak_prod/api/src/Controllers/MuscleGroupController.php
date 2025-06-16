<?php
namespace App\Controllers;

use App\Managers\MuscleGroupManager;
use App\Models\MuscleGroup;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class MuscleGroupController
{
    private MuscleGroupManager $muscleGroupManager;

    public function __construct(MuscleGroupManager $muscleGroupManager)
    {
        $this->muscleGroupManager = $muscleGroupManager;
    }

    public function getAllMuscleGroups(Request $request, Response $response): Response
    {
        $muscleGroups = $this->muscleGroupManager->getAllMuscleGroups();
        $response->getBody()->write(json_encode($muscleGroups));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getMuscleGroup(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $muscleGroup = $this->muscleGroupManager->getMuscleGroupById($id);

        if (!$muscleGroup) {
            $payload = json_encode(['message' => 'Groupe musculaire non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode($muscleGroup));
        return $response->withHeader('Content-Type', 'application/json');
    }

public function createMuscleGroup(Request $request, Response $response): Response
{
    $data = (array)$request->getParsedBody();
    if (empty($data['name']) || empty($data['description'])) {
        $payload = json_encode(['message' => 'Données incomplètes']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Création de l'objet MuscleGroup
    $muscleGroup = new MuscleGroup($data);
    $muscleGroup->setName($data['name']);


    if ($this->muscleGroupManager->createMuscleGroup($muscleGroup)) {
        $payload = json_encode(['message' => 'Groupe musculaire créé avec succès']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    } else {
        $payload = json_encode(['message' => 'Erreur lors de la création']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
}


    public function updateMuscleGroup(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $data = (array)$request->getParsedBody();

        $muscleGroup = $this->muscleGroupManager->getMuscleGroupById($id);
        if (!$muscleGroup) {
            $payload = json_encode(['message' => 'Groupe musculaire non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        if (isset($data['name'])) {
            $muscleGroup->setName($data['name']);
        }


        if ($this->muscleGroupManager->updateMuscleGroup($muscleGroup)) {
            $payload = json_encode(['message' => 'Groupe musculaire mis à jour avec succès']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $payload = json_encode(['message' => 'Erreur lors de la mise à jour']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

    public function deleteMuscleGroup(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];

        if ($this->muscleGroupManager->deleteMuscleGroup($id)) {
            $payload = json_encode(['message' => 'Groupe musculaire supprimé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $payload = json_encode(['message' => 'Erreur lors de la suppression']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }
}
