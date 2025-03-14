<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../routes/api.php';
require_once __DIR__ . '/../controllers/AuthController.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST"); // ✅ Autorise GET et POST
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // ✅ Autorise les headers JSON

$authController = new AuthController($pdo);

// Vérifie si on est en POST (login/register)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['route'])) {
        if ($_GET['route'] === 'login') {
            $authController->login();
        } elseif ($_GET['route'] === 'register') {
            $authController->register();
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Route non trouvée"]);
        }
    }
    exit();
}

// Si la requête est GET, on laisse `api.php` gérer les routes
require_once __DIR__ . '/../routes/api.php';

