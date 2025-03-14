<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../managers/UserManager.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/auth.php';
require_once __DIR__ . '/../controllers/UserController.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;
$url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];
class AuthController {
    private UserManager $userManager;
    private string $secret_key;

    public function __construct(PDO $pdo) {
        $this->userManager = new UserManager($pdo);
        $this->secret_key = $_ENV['JWT_SECRET'] ?? 'default_secret_key';
    }

    // Connexion utilisateur
    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["message" => "Email et mot de passe requis"]);
            return;
        }
    
        $user = $this->userManager->getUserByEmail($data['email']);
        
        if (!$user) {
            error_log("Utilisateur non trouvé !");
            http_response_code(401);
            echo json_encode(["message" => "Identifiants incorrects"]);
            return;
        }
    
        $passwordForm = trim($data['password']);
        $passwordDB = $user->getPassword();
    
       

    
        // Vérification via password_verify
        if (!password_verify($passwordForm, $passwordDB)) {
            error_log("❌ Échec de la vérification !");
            http_response_code(401);
            echo json_encode(["message" => "Identifiants incorrects"]);
            return;
        }
    
        error_log("✅ Mot de passe vérifié avec succès !");
        
        $this->secret_key = getenv('JWT_SECRET') ?: ($_ENV['JWT_SECRET'] ?? 'default_secret_key');
        $payload = [
            "iat" => time(),
            "exp" => time() + (60 * 60),
            "user_id" => $user->getId(),
            "email" => $user->getEmail(),
            "role" => $user->getRole()
        ];
    
        $jwt = JWT::encode($payload, $this->secret_key, 'HS256');
    
        http_response_code(200);
        echo json_encode(["message" => "Connexion réussie", "token" => $jwt]);
    }
    
    

    // Inscription d'un utilisateur
    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password'])) {
            error_log("Erreur : Champs requis manquants");
            http_response_code(400);
            echo json_encode(["message" => "Tous les champs sont requis"]);
            exit;
        }
    
        if ($this->userManager->getUserByEmail($data['email'])) {
            error_log("Erreur : L'utilisateur existe déjà");
            http_response_code(400);
            echo json_encode(["message" => "L'utilisateur existe déjà"]);
            exit;
        }
    
        $user = new User([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'user'
        ]);
    
        if ($this->userManager->createUser($user)) {
            error_log("Utilisateur créé avec succès");
            http_response_code(201);
            echo json_encode(["message" => "Utilisateur créé avec succès"]);
        } else {
            error_log("Erreur lors de la création de l'utilisateur en base de données");
            http_response_code(500);
            echo json_encode(["message" => "Erreur lors de la création de l'utilisateur"]);
        }
    }
    

    // Vérification du token (pour sécuriser les routes)
    public function verifyToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
}
$headers = getallheaders();
$authController = new AuthController($pdo);

$publicRoutes = [
    'users',
    'users/(\d+)',
    'users/login',
    'users/login/(\d+)',
    'users/register',
    'templates',
    'templates/(\d+)',
    'exercises',
    'exercises/(\d+)',
    'muscle-groups',
    'muscle-groups/(\d+)',
    
];

$isPublicRoute = false;

// Vérifier si la route demandée est publique
foreach ($publicRoutes as $route) {
    if (preg_match('#^' . $route . '$#', $url)) {
        $isPublicRoute = true;
        break;
    }
}

if (!$isPublicRoute) {
    // Routes nécessitant un token
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $decodedToken = $authController->verifyToken($token);

        if (!$decodedToken) {
            http_response_code(401);
            echo json_encode(["message" => "Token invalide"]);
            exit;
        }
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Accès refusé, token requis"]);
        exit;
    }
}

?>
