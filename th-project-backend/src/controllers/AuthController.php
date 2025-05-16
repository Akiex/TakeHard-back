<?php
namespace App\Controllers;

use App\Services\JwtService;
use App\Managers\UserManager;
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthController
{
    private UserManager $userManager;
    private JwtService $jwtService;

    public function __construct(UserManager $userManager, JwtService $jwtService)
    {
        $this->userManager = $userManager;
        $this->jwtService = $jwtService;
    }

    public function login(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        if (empty($data['email']) || empty($data['password'])) {
            $payload = json_encode(['message' => 'Email et mot de passe requis']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $user = $this->userManager->getUserByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user->getPassword())) {
            $payload = json_encode(['message' => 'Identifiants incorrects']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        $token = $this->jwtService->generateToken([
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
        ]);

        $response->getBody()->write(json_encode([
            'message' => 'Connexion réussie',
            'token' => $token,
            'is_admin' => $user->getRole() === 'admin',
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function register(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        $required = ['first_name', 'last_name', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $payload = json_encode(['message' => 'Tous les champs sont requis']);
                $response->getBody()->write($payload);
                return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
            }
        }

        if ($this->userManager->getUserByEmail($data['email'])) {
            $payload = json_encode(['message' => "L'utilisateur existe déjà"]);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(409);
        }

        $user = new User([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => $data['password'],
            'role'       => 'user',
        ]);
        $this->userManager->createUser($user);

        $response->getBody()->write(json_encode(['message' => 'Utilisateur créé avec succès']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function verifyToken(Request $request, Response $response, array $args): Response
    {
        $token = $args['token'] ?? '';
        try {
            $decoded = $this->jwtService->decode($token);
            $response->getBody()->write(json_encode(['valid' => true, 'data' => (array)$decoded]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['valid' => false, 'error' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }
    }
}
