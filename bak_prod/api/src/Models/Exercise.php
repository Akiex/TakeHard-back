<?php
namespace App\Models;

class Exercise {
    private int $id;
    private string $name;
    private string $description;
    private array $muscleGroups = []; // Tableau pour stocker les groupes musculaires associés

    public function __construct(array $data) {
        $this->hydrate($data);
    }

    private function hydrate(array $data): void {
        foreach ($data as $key => $value) {
            $formatted = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            $method = 'set' . ucfirst($formatted);
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

    public function getDescription(): string {
        return $this->description;
    }

    // Méthode pour obtenir la liste des groupes musculaires associés à cet exercice
    public function getMuscleGroups(): array {
        return $this->muscleGroups;
    }

    // Méthode pour ajouter un groupe musculaire à cet exercice
    public function addMuscleGroup(MuscleGroup $muscleGroup): void {

        $this->muscleGroups[] = $muscleGroup;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = trim($name);
    }

    public function setDescription(string $description): void {
        $this->description = trim($description);
    }

    // Méthode pour lier plusieurs groupes musculaires à cet exercice
    public function setMuscleGroups(array $muscleGroups): void {
        $this->muscleGroups = $muscleGroups;
    }
}
