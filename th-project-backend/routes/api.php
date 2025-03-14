<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/auth.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/TemplateController.php';
require_once __DIR__ . '/../controllers/MuscleGroupController.php';
require_once __DIR__ . '/../controllers/SetController.php';
require_once __DIR__ . '/../controllers/ExerciseController.php';
require_once __DIR__ . '/../controllers/AuthController.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Initialisation des contrôleurs
$UserController = new UserController($pdo);
$TemplateController = new TemplateController($pdo);
$MuscleGroupController = new MuscleGroupController($pdo);
$SetController = new SetController($pdo);
$ExerciseController = new ExerciseController($pdo);
$AuthController = new AuthController($pdo);

// Analyse de l'URL et de la méthode
$url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];



// === ROUTES POUR LES UTILISATEURS ===
if (preg_match('/^users\/?$/', $url)) {
    // Route pour inscription
    if ($method === 'POST') {
        // Pas de token requis ici
        echo json_encode($AuthController->register());
    }
    // Route pour récupérer tous les utilisateurs (requiert un token)
    elseif ($method === 'GET') {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["message" => "Token requis"]);
            exit();
        }
        $token = str_replace("Bearer ", "", $headers["Authorization"]);
        $userId = verifyJWT($token);

        if (!$userId) {
            http_response_code(401);
            echo json_encode(["message" => "Token invalide"]);
            exit();
        }

        echo json_encode($UserController->getAllUsers());
    }
    // Méthode non autorisée
    else {
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
}

// Route pour login
elseif (preg_match('/^users\/login$/', $url)) {
    
    if ($method === 'POST') {
        // Récupération et affichage correct de la réponse
        $response = $AuthController->login();
        print_r($response, true);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
}


// Route pour utilisateur spécifique
elseif (preg_match('/^users\/(\d+)$/', $url, $matches)) {
    $id = (int) $matches[1];
    if ($method === 'GET') {
        echo json_encode($UserController->getUser($id));
    } elseif ($method === 'DELETE') {
        echo json_encode($UserController->deleteUser($id));
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
}

// === ROUTES POUR LES TEMPLATES ===
elseif (preg_match('/^templates\/?$/', $url)) {
    if ($method === 'GET') {
        echo json_encode($TemplateController->getAllTemplates());
    } elseif ($method === 'POST') {
        echo json_encode($TemplateController->createTemplate());
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
} elseif (preg_match('/^templates\/(\d+)$/', $url, $matches)) {
    $id = (int) $matches[1];
    if ($method === 'GET') {
        echo json_encode($TemplateController->getTemplate($id));
    } elseif ($method === 'PUT') {
        echo json_encode($TemplateController->updateTemplate($id));
    } elseif ($method === 'DELETE') {
        echo json_encode($TemplateController->deleteTemplate($id));
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
}

// === AUTRES ROUTES (SETS, MUSCLE GROUPS, ETC.) ===
// Sets
elseif (preg_match('/^sets\/?$/', $url)) {
    if ($method === 'GET') {
        echo json_encode($SetController->getAllSets());
    } elseif ($method === 'POST') {
        echo json_encode($SetController->createSet());
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
} elseif (preg_match('/^sets\/(\d+)$/', $url, $matches)) {
    $id = (int) $matches[1];
    if ($method === 'GET') {
        echo json_encode($SetController->getSet($id));
    } elseif ($method === 'PUT') {
        echo json_encode($SetController->updateSet($id));
    } elseif ($method === 'DELETE') {
        echo json_encode($SetController->deleteSet($id));
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
}

// === ROUTE INCONNUE ===
else {
    http_response_code(404);
    echo json_encode(["message" => "Route non trouvée"]);
}
