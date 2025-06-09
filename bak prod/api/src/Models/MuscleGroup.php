<?php
namespace App\Models;

class MuscleGroup {
    private int $id;
    private string $name;
    private array $exercises = []; // Ajout d'une propriété pour stocker les exercices liés à ce groupe musculaire

    public function __construct(array $data) {
        $this->hydrate($data);
    }

    private function hydrate(array $data): void {
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

    public function getName(): string {
        return $this->name;
    }

    // Méthode pour ajouter un exercice à ce groupe musculaire
    public function addExercise(Exercise $exercise): void {
        $this->exercises[] = $exercise;
    }

    // Récupérer la collection d'exercices liés à ce groupe musculaire
    public function getExercises(): array {
        return $this->exercises;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = trim($name);
    }
}
