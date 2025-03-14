<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verifyJWT($token) {
    try {
        $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        return $decoded->user_id; // Retourne l'ID de l'utilisateur
    } catch (Exception $e) {
        return false; // Token invalide
    }
}
function isAdmin($userId, $pdo) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    return $user && $user['role'] === 'admin';
}
?>
