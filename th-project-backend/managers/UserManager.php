<?php

require_once __DIR__ . '/../models/User.php';

class UserManager {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer tous les utilisateurs
    public function getAllUsers(): array {
        $sql = "SELECT id, first_name, last_name, email, role FROM users";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un utilisateur
    public function createUser(User $user): bool {
        // Hachage du mot de passe
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
    
        $sql = "INSERT INTO users (first_name, last_name, email, password, role) VALUES (:first_name, :last_name, :email, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
    
        // Utilise le mot de passe haché
        $result = $stmt->execute([
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'email'      => $user->getEmail(),
            'password'   => $hashedPassword,
            'role'       => $user->getRole(),
        ]);
    
        if (!$result) {
            error_log("Erreur SQL : " . print_r($stmt->errorInfo(), true));
        }
    
        return $result;
    }
    
    
    public function updateUser(User $user): bool {
        $sql = "UPDATE users 
                SET first_name = :first_name, 
                    last_name = :last_name, 
                    email = :email, 
                    password = :password, 
                    role = :role 
                WHERE id = :id";
                
        $stmt = $this->pdo->prepare($sql);
    
        $result = $stmt->execute([
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'email'      => $user->getEmail(),
            'password'   => $user->getPassword(),
            'role'       => $user->getRole(),
            'id'         => $user->getId(),
        ]);
    
        if (!$result) {
            error_log("Erreur SQL : " . print_r($stmt->errorInfo(), true));
        }
    
        return $result;
    }
    // Récupérer un utilisateur par son email
    public function getUserByEmail(string $email): ?User {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    // Récupérer un utilisateur par son ID
    public function getUserById(int $id): ?User {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    // Supprimer un utilisateur
    public function deleteUser(int $id): bool {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}

