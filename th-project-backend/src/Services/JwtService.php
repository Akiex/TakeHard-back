<?php
namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService {
    private string $secret;

    public function __construct(string $secret) {
        $this->secret = $secret;
    }

    /**
     * Génère un token JWT pour un utilisateur.
     *
     * @param array $data
     * @return string
     */
public function generateToken(array $data): string {
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600; // 1 heure

    $payload = array_merge($data, [
        'iat' => $issuedAt,
        'exp' => $expirationTime
    ]);

    return JWT::encode($payload, $this->secret, 'HS256');
}

    /**
     * Décode un token JWT.
     *
     * @param string $jwt
     * @return object
     * @throws \Firebase\JWT\ExpiredException
     * @throws \Firebase\JWT\BeforeValidException
     * @throws \Firebase\JWT\SignatureInvalidException
     */
    public function decode(string $jwt) {
        return JWT::decode($jwt, new Key($this->secret, 'HS256'));
    }

    /**
     * Vérifie si le token est valide.
     *
     * @param string $jwt
     * @return bool
     */
    public function verify(string $jwt): bool {
        try {
            $this->decode($jwt);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
