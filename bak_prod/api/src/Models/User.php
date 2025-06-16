<?php
namespace App\Models;

use InvalidArgumentException;

class User
{
    private int $id;
    private string $firstName = '';
    private string $lastName = '';
    private string $email = '';
    private string $password = '';
    private string $role = 'user';
    private string $createdAt = '';

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    private function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $formatted = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            $method = 'set' . $formatted;
            if (method_exists($this, $method)) {
                $this->$method($value);
            } else {
                error_log("No setter found for key: $key");
            }
        }
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    // Setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = trim($firstName);
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = trim($lastName);
    }

    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address.');
        }
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters.');
        }
        $this->password = $password;
    }

    public function setRole(string $role): void
    {
        $validRoles = ['user', 'admin'];
        if (!in_array($role, $validRoles, true)) {
            throw new InvalidArgumentException('Invalid role.');
        }
        $this->role = $role;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}