<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../managers/UserManager.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController {
    private UserManager $userManager;

    public function __construct(PDO $pdo) {

        $this->userManager = new UserManager($pdo);
    }

    public function createUser() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["message" => "Champs requis manquants"]);
            return;
        }
        $this->userManager->createUser(new User($data));
        http_response_code(201);
        echo json_encode(["message" => "Utilisateur créé avec succès"]);
    }
    public function updateUser($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$this->userManager->getUserById($id)) {
            http_response_code(404);
            echo json_encode(["message" => "Utilisateur non trouvé"]);
            return;
        }
        $this->userManager->updateUser($id, $data);
        http_response_code(200);
        echo json_encode(["message" => "Utilisateur mis à jour"]);
    }
    public function deleteUser($id) {
        if (!$this->userManager->getUserById($id)) {
            http_response_code(404);
            echo json_encode(["message" => "Utilisateur non trouvé"]);
            return;
        }
        $this->userManager->deleteUser($id);
        http_response_code(200);
        echo json_encode(["message" => "Utilisateur supprimé avec succès"]);
    }
}

