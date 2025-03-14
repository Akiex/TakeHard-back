<?php
class User {
    private int $id;
    private string $first_name = ''; 
    private string $last_name = '';
    private string $email = '';
    private string $password = '';
    private string $role = 'user';
    private string $createdAt = '';

    public function __construct(array $data) {
        $this->hydrate($data);
    }

    private function hydrate(array $data): void {
        foreach ($data as $key => $value) {
            $key = str_replace('_', '', ucwords($key, '_'));
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                
                $this->$method($value);
            } else {
                error_log("Aucun setter trouvé pour la clé : $key");
            }
        }
    }
    

    // Getters
    public function getId(): int { return $this->id; }
    public function getFirstName(): string { return $this->first_name; }
    public function getLastName(): string { return $this->last_name; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getRole(): string { return $this->role; }
    public function getCreatedAt(): string { return $this->createdAt; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }
    public function setFirstName(string $first_name): void { 
        $this->first_name = trim($first_name); 
    }

    public function setLastName(string $last_name): void { 
        $this->last_name = trim($last_name); 
    }

    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email invalide.');
        }
        $this->email = $email;
    }

    public function setPassword(string $password): void {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Le mot de passe doit contenir au moins 8 caractères.');
        }
        $this->password = $password; // Aucun hachage ici
    }

    public function setRole(string $role = 'user'): void {
        $validRoles = ['user', 'admin'];
        if (!in_array($role, $validRoles)) {
            throw new InvalidArgumentException('Rôle non valide.');
        }
        $this->role = $role ?? 'user'; // Utilisation de 'user' si $role est null
    }
    }

