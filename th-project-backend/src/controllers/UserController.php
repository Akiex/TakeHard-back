<?php
namespace App\Controllers;

use App\Managers\UserManager;
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Services\ValidationService;
use Firebase\JWT\JWT;

class UserController
{
    private UserManager $userManager;
    private ValidationService $validationService;

    public function __construct(UserManager $userManager, ValidationService $validationService)
    {
        $this->userManager = $userManager;
        $this->validationService = $validationService;
    }

    public function createUser(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        if (empty($data['email']) || empty($data['password'])) {
            $payload = json_encode(['message' => 'Champs requis manquants']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $user = new User($data);
        $this->userManager->createUser($user);

        $payload = json_encode(['message' => 'Utilisateur créé avec succès']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function getAllUsers(Request $request, Response $response): Response
    {
        $users = $this->userManager->getAllUsers();
        $response->getBody()->write(json_encode($users));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getUser(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $user = $this->userManager->getUserById($id);
        if (!$user) {
            $payload = json_encode(['message' => 'Utilisateur non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        $response->getBody()->write(json_encode($user));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updateUser(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $existing = $this->userManager->getUserById($id);
        if (!$existing) {
            $payload = json_encode(['message' => 'Utilisateur non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $data = (array)$request->getParsedBody();
        $existing->setFirstName($data['first_name'] ?? $existing->getFirstName());
        $existing->setLastName($data['last_name']  ?? $existing->getLastName());
        $existing->setEmail($data['email']        ?? $existing->getEmail());
        $existing->setPassword($data['password']  ?? $existing->getPassword());
        $existing->setRole($data['role']          ?? $existing->getRole());

        $this->userManager->updateUser($existing);

        $payload = json_encode(['message' => 'Utilisateur mis à jour']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteUser(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $existing = $this->userManager->getUserById($id);
        if (!$existing) {
            $payload = json_encode(['message' => 'Utilisateur non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $this->userManager->deleteUser($id);
        $payload = json_encode(['message' => 'Utilisateur supprimé avec succès']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}