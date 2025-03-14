<?php
class Set {
    private int $id;
    private int $sets;
    private int $reps;
    private int $rest_time;
    private ?float $weight; // Nullable car le poids peut être NULL

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

    public function getId(): int { return $this->id; }
    public function getSets(): int { return $this->sets; }
    public function getReps(): int { return $this->reps; }
    public function getRestTime(): int { return $this->rest_time; }
    public function getWeight(): ?float { return $this->weight; }

    public function setId(int $id): void { $this->id = $id; }
    public function setSets(int $sets): void { $this->sets = $sets; }
    public function setReps(int $reps): void { $this->reps = $reps; }
    public function setRestTime(int $rest_time): void { $this->rest_time = $rest_time; }
    public function setWeight(?float $weight): void { $this->weight = $weight; }
}
