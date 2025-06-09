<?php
namespace App\Models;

class Template {
    private int $id;
    private int $user_id;
    private string $name;
    private string $description;
    private bool $is_public;
    private ?User $user = null;
    private array $sets = [];  // Ajout d'une propriété pour stocker les sets liés

    public function __construct(array $data) {
        $this->hydrate($data);
    }

    private function hydrate(array $data) {
        foreach ($data as $key => $value) {
            $key = str_replace('_', '', ucwords($key, '_'));
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUserId(): int {
        return $this->user_id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getIsPublic(): bool {
        return $this->is_public;
    }

    // Ajoutez une méthode pour lier un User au Template
    public function setUser(User $user): void {
        $this->user = $user;
    }

    // Optionnel: récupérer l'objet User lié à ce Template
    public function getUser(): ?User {
        return $this->user;
    }

    // Méthode pour ajouter un Set à ce Template
    public function addSet(Set $set): void {
        $this->sets[] = $set;
    }

    // Récupérer la collection de sets liés à ce template
    public function getSets(): array {
        return $this->sets;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setUserId(int $user_id): void {
        $this->user_id = $user_id;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function setIsPublic(bool $is_public): void {
        $this->is_public = $is_public;
    }
}
