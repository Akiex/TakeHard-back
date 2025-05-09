<?php
namespace App\Services;

class ValidationService {
    /**
     * Valide un email.
     *
     * @param string $email
     * @return bool
     */
    public function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valide une chaîne de caractères non vide.
     *
     * @param string $input
     * @return bool
     */
    public function validateNotEmpty(string $input): bool {
        return !empty($input);
    }

    /**
     * Valide un mot de passe (doit être au moins 8 caractères).
     *
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password): bool {
        return strlen($password) >= 8;
    }
}
