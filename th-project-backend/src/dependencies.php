<?php
use Psr\Container\ContainerInterface;

use Slim\App;

return function(App $app) {
    $c = $app->getContainer();

    // PDO
    $c->set('db', function() {
        return new PDO(
            $_ENV['DB_DSN'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASS'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    });

    // Managers
    $c->set(\App\Managers\UserManager::class, fn(ContainerInterface $c) => new \App\Managers\UserManager($c->get('db')));
    $c->set(\App\Managers\ExerciseManager::class, fn(ContainerInterface $c) => new \App\Managers\ExerciseManager($c->get('db')));
    $c->set(\App\Managers\MuscleGroupManager::class, fn(ContainerInterface $c) => new \App\Managers\MuscleGroupManager($c->get('db')));
    $c->set(\App\Managers\SetManager::class, fn(ContainerInterface $c) => new \App\Managers\SetManager($c->get('db')));
    $c->set(\App\Managers\TemplateManager::class, fn(ContainerInterface $c) => new \App\Managers\TemplateManager($c->get('db')));

    // Services
    $c->set(\App\Services\ValidationService::class, fn() => new class {
        public function validate(array $data, array $rules): array {
            // Implémentation basique de validation (exemple)
            return [];
        }
    });

    $c->set(\App\Services\JwtService::class, fn() => new class($_ENV['JWT_SECRET']) {
        private string $secret;
        public function __construct(string $secret) {
            $this->secret = $secret;
        }
        public function encode(array $payload): string {
            return base64_encode(json_encode($payload)); // Stub simplifié
        }
        public function decode(string $token): array {
            return json_decode(base64_decode($token), true); // Stub simplifié
        }
    });

    // Controllers
    $c->set(\App\Controllers\UserController::class, fn(ContainerInterface $c) =>
        new \App\Controllers\UserController(
            $c->get(\App\Managers\UserManager::class),
            $c->get(\App\Services\ValidationService::class)
        )
    );

    $c->set(\App\Controllers\AuthController::class, fn(ContainerInterface $c) =>
        new \App\Controllers\AuthController(
            $c->get(\App\Managers\UserManager::class),
            $c->get(\App\Services\JwtService::class)
        )
    );

    $c->set(\App\Controllers\ExerciseController::class, fn(ContainerInterface $c) =>
        new \App\Controllers\ExerciseController(
            $c->get(\App\Managers\ExerciseManager::class),
            $c->get(\App\Services\ValidationService::class)
        )
    );

    $c->set(\App\Controllers\MuscleGroupController::class, fn(ContainerInterface $c) =>
        new \App\Controllers\MuscleGroupController($c->get(\App\Managers\MuscleGroupManager::class)));

    $c->set(\App\Controllers\SetController::class, fn(ContainerInterface $c) =>
        new \App\Controllers\SetController($c->get(\App\Managers\SetManager::class)));

    $c->set(\App\Controllers\TemplateController::class, fn(ContainerInterface $c) =>
        new \App\Controllers\TemplateController($c->get(\App\Managers\TemplateManager::class)));

    // Error handler
    $c->set('errorHandler', function() {
        return function($request, $response, $exception) {
            error_log($exception->getMessage());
            $response->getBody()->write(json_encode(['error' => 'Internal Server Error']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        };
    });
};