<?php
namespace App\Controllers;

use App\Managers\SetManager;
use App\Models\Set;
use App\Models\Exercise;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class SetController
{
    private SetManager $setManager;

    public function __construct(SetManager $setManager)
    {
        $this->setManager = $setManager;
    }

    // GET /sets
    public function getAllSets(Request $request, Response $response): Response
    {
        $sets = $this->setManager->getAllSets();
        $payload = array_map(function(Set $s) {
            return [
                'id'         => $s->getId(),
                'weight'     => $s->getWeight(),
                'sets'       => $s->getSets(),
                'reps'       => $s->getReps(),
                'rest_time'  => $s->getRestTime(),
                'exercises'  => array_map(function(Exercise $ex) {
                    return [
                        'id'   => $ex->getId(),
                        'name' => $ex->getName(),
                    ];
                }, $s->getExercises()),
            ];
        }, $sets);

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // GET /sets/{id}
    public function getSet(Request $request, Response $response, array $args): Response
    {
        $set = $this->setManager->getSetById((int)$args['id']);
        if (!$set) {
            $response->getBody()->write(json_encode(['message' => 'Set non trouvé']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $data = [
            'id'         => $set->getId(),
            'weight'     => $set->getWeight(),
            'sets'       => $set->getSets(),
            'reps'       => $set->getReps(),
            'rest_time'  => $set->getRestTime(),
            'exercises'  => array_map(fn(Exercise $ex) => [
                'id'   => $ex->getId(),
                'name' => $ex->getName()
            ], $set->getExercises()),
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // POST /sets
    public function createSet(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        $set = $this->setManager->createSet($data);

        $payload = [
            'id'        => $set->getId(),
            'weight'    => $set->getWeight(),
            'sets'      => $set->getSets(),
            'reps'      => $set->getReps(),
            'rest_time' => $set->getRestTime(),
            'exercises' => array_map(fn(Exercise $ex) => [
                'id'   => $ex->getId(),
                'name' => $ex->getName()
            ], $set->getExercises()),
        ];

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // PUT /sets/{id}
    public function updateSet(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $data = (array)$request->getParsedBody();
        $set = $this->setManager->updateSet($id, $data);
        if (!$set) {
            $response->getBody()->write(json_encode(['message' => 'Set non trouvé']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $payload = [
            'id'        => $set->getId(),
            'weight'    => $set->getWeight(),
            'sets'      => $set->getSets(),
            'reps'      => $set->getReps(),
            'rest_time' => $set->getRestTime(),
            'exercises' => array_map(fn(Exercise $ex) => [
                'id'   => $ex->getId(),
                'name' => $ex->getName()
            ], $set->getExercises()),
        ];

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // DELETE /sets/{id}
    public function deleteSet(Request $request, Response $response, array $args): Response
    {
        $success = $this->setManager->deleteSet((int)$args['id']);
        if (!$success) {
            $response->getBody()->write(json_encode(['message' => 'Erreur suppression']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        $response->getBody()->write(json_encode(['message' => 'Set supprimé avec succès']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}