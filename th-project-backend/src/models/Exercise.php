<?php
namespace App\Models;

class Exercise {
    private int $id;
    private string $name;
    private string $description;
    private int $muscleGroupId;

    public function __construct(array $data) {
        $this->hydrate($data);
    }

    private function hydrate(array $data): void {
        foreach ($data as $key => $value) {
            $formatted = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            $method = 'set' . $formatted;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getMuscleGroupId(): int {
        return $this->muscleGroupId;
    }

    // Setters
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setName(string $name): void {
        $this->name = trim($name);
    }

    public function setDescription(string $description): void {
        $this->description = trim($description);
    }

    public function setMuscleGroupId(int $muscleGroupId): void {
        $this->muscleGroupId = $muscleGroupId;
    }
}
