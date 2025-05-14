<?php
use Psr\Container\ContainerInterface;
use DI\ContainerBuilder;
use App\Managers\UserManager;
use App\Managers\ExerciseManager;
use App\Managers\MuscleGroupManager;
use App\Managers\SetManager;
use App\Managers\TemplateManager;
use App\Services\ValidationService;
use App\Services\JwtService;
use App\Error\HttpErrorHandler;
use Psr\Http\Message\ResponseFactoryInterface;



return [
    HttpErrorHandler::class => function (ContainerInterface $c) {
    return new HttpErrorHandler(
        $c->get(ResponseFactoryInterface::class)
        // Optionnel : ajouter un logger ici si tu en as un
        // , $c->get(LoggerInterface::class)
    );
},
    // Paramètres de configuration
    'settings' => [
        'db' => [
            'dsn'     => $_ENV['DB_DSN'],
            'user'    => $_ENV['DB_USER'],
            'pass'    => $_ENV['DB_PASS'],
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        ],
        'jwt' => [
            'secret' => $_ENV['JWT_SECRET'] ?? '',
        ],
    ],

    // Service PDO
    PDO::class => function (ContainerInterface $c) {
        $db = $c->get('settings')['db'];
        return new PDO(
            $db['dsn'],
            $db['user'],
            $db['pass'],
            $db['options']
        );
    },
    'db' => DI\get(PDO::class),

    // Managers
    UserManager::class => function (ContainerInterface $c) {
        return new UserManager($c->get('db'));
    },
    ExerciseManager::class => function (ContainerInterface $c) {
        return new ExerciseManager($c->get('db'));
    },
    MuscleGroupManager::class => function (ContainerInterface $c) {
        return new MuscleGroupManager($c->get('db'));
    },
    SetManager::class => function (ContainerInterface $c) {
        return new SetManager($c->get('db'));
    },
    TemplateManager::class => function (ContainerInterface $c) {
        return new TemplateManager($c->get('db'));
    },

    // Services
    ValidationService::class => function () {
        return new ValidationService();
    },
    JwtService::class => function (ContainerInterface $c) {
        $secret = $c->get('settings')['jwt']['secret'];
        return new JwtService($secret);
    },

    // Controllers (optionnel si auto-wiring activé)
    App\Controllers\UserController::class => function (ContainerInterface $c) {
        return new App\Controllers\UserController(
            $c->get(UserManager::class),
            $c->get(ValidationService::class)
        );
    },
    App\Controllers\AuthController::class => function (ContainerInterface $c) {
        return new App\Controllers\AuthController(
            $c->get(UserManager::class),
            $c->get(JwtService::class)
        );
    },
    App\Controllers\ExerciseController::class => function (ContainerInterface $c) {
        return new App\Controllers\ExerciseController(
            $c->get(ExerciseManager::class),
        );
    },
    App\Controllers\MuscleGroupController::class => function (ContainerInterface $c) {
        return new App\Controllers\MuscleGroupController(
            muscleGroupManager: $c->get(MuscleGroupManager::class),
        );
    },
    App\Controllers\SetController::class => function (ContainerInterface $c) {
        return new App\Controllers\SetController(
            $c->get(SetManager::class)
        );
    },
    App\Controllers\TemplateController::class => function (ContainerInterface $c) {
        return new App\Controllers\TemplateController(
            $c->get(TemplateManager::class)
        );
    },

];
