<?php
namespace App\Managers;

use App\Models\User;
use PDO;
use PDOException;

class UserManager
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Retourne tous les utilisateurs (array of associative arrays)
     */
    public function getAllUsers(): array
    {
        $sql = 'SELECT id, first_name, last_name, email, role FROM users';
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function createUser(User $user): bool
    {
        $hashedPassword = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (first_name, last_name, email, password, role)'
             . ' VALUES (:first_name, :last_name, :email, :password, :role)';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'email'      => $user->getEmail(),
            'password'   => $hashedPassword,
            'role'       => $user->getRole(),
        ]);
    }

    /**
     * Met à jour un utilisateur existant
     */
    public function updateUser(User $user): bool
    {
        // Hachage si nouveau mot de passe
        $password = $user->getPassword();
        $hashed  = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

        $sql = 'UPDATE users
                SET first_name = :first_name,
                    last_name  = :last_name,
                    email      = :email,
                    password   = :password,
                    role       = :role
                WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'first_name' => $user->getFirstName(),
            'last_name'  => $user->getLastName(),
            'email'      => $user->getEmail(),
            'password'   => $hashed ?? $user->getPassword(),
            'role'       => $user->getRole(),
            'id'         => $user->getId(),
        ]);
    }

    /**
     * Récupère un utilisateur par son email
     */
    public function getUserByEmail(string $email): ?User
    {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new User($data) : null;
    }

    /**
     * Récupère un utilisateur par son ID
     */
    public function getUserById(int $id): ?User
    {
        try {
            $sql = 'SELECT * FROM users WHERE id = :id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data ? new User($data) : null;
        } catch (PDOException $e) {
            // Log de l'erreur si besoin
            return null;
        }
    }

    /**
     * Supprime un utilisateur
     */
    public function deleteUser(int $id): bool
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
