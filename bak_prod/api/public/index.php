<?php
// public/index.php

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use App\Middleware\CorsMiddleware; // ← on importe le middleware

require __DIR__ . '/../vendor/autoload.php';
Dotenv::createImmutable(__DIR__ . '/../')->load();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../src/dependencies.php');
$container = $containerBuilder->build();

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->setBasePath('/api');
$app->addBodyParsingMiddleware();
$app->add(function ($request, $handler) {
    $override = $request->getHeaderLine('X-HTTP-Method-Override');
    if ($override) {
        $request = $request->withMethod($override);
    }
    return $handler->handle($request);
});

// 2) CORS
$app->add(new CorsMiddleware());

// 3) Routing
$app->addRoutingMiddleware();



// Gestionnaire d’erreurs (errorHandler)
if ($container->has('errorHandler')) {
    $app->addErrorMiddleware(true, true, true)
        ->setDefaultErrorHandler($container->get('errorHandler'));
} else {
    $app->addErrorMiddleware(true, true, true);
}

// (Optionnel) définir le base path si vos routes sont sous /api


// ───── Routes ─────
$app->get('/', function ($req, $res) {
    $res->getBody()->write(json_encode(['message' => 'API Slim opérationnelle !']));
    return $res->withHeader('Content-Type', 'application/json');
});

$app->group('/users', function (RouteCollectorProxy $group) {
    // Création d’un utilisateur
    $group->post('', App\Controllers\UserController::class . ':createUser');

    // Authentification / Inscription
    $group->post('/login',    App\Controllers\AuthController::class . ':login');
    $group->post('/register', App\Controllers\AuthController::class . ':register');

    // Récupération
    $group->get('',         App\Controllers\UserController::class . ':getAllUsers');
    $group->get('/{id}',    App\Controllers\UserController::class . ':getUser');

    // Modification & suppression
    $group->put('/{id}',    App\Controllers\UserController::class . ':updateUser');
    $group->delete('/{id}', App\Controllers\UserController::class . ':deleteUser');
});

// Exercises
$app->group('/exercises', function (RouteCollectorProxy $group) {
    $group->get('',    App\Controllers\ExerciseController::class . ':getAllExercises');
    $group->post('',   App\Controllers\ExerciseController::class . ':createExercise');
    $group->get('/{id}',  App\Controllers\ExerciseController::class . ':getExercise');
    $group->put('/{id}',  App\Controllers\ExerciseController::class . ':updateExercise');
    $group->delete('/{id}', App\Controllers\ExerciseController::class . ':deleteExercise');
});

// Sets
$app->group('/sets', function (RouteCollectorProxy $group) {
    $group->get('',    App\Controllers\SetController::class . ':getAllSets');
    $group->post('',   App\Controllers\SetController::class . ':createSet');
    $group->get('/{id}',  App\Controllers\SetController::class . ':getSet');
    $group->put('/{id}',  App\Controllers\SetController::class . ':updateSet');
    $group->delete('/{id}', App\Controllers\SetController::class . ':deleteSet');
});

// Templates
$app->group('/templates', function (RouteCollectorProxy $group) {
    $group->get('',    App\Controllers\TemplateController::class . ':getAllTemplates');
    $group->post('',   App\Controllers\TemplateController::class . ':createTemplate');
    $group->get('/{id}',  App\Controllers\TemplateController::class . ':getTemplate');
    $group->put('/{id}',  App\Controllers\TemplateController::class . ':updateTemplate');
    $group->delete('/{id}', App\Controllers\TemplateController::class . ':deleteTemplate');
});

// Muscle‐groups
$app->group('/muscle-groups', function (RouteCollectorProxy $group) {
    $group->get('',    App\Controllers\MuscleGroupController::class . ':getAllMuscleGroups');
    $group->post('',   App\Controllers\MuscleGroupController::class . ':createMuscleGroup');
    $group->get('/{id}',  App\Controllers\MuscleGroupController::class . ':getMuscleGroup');
    $group->put('/{id}',  App\Controllers\MuscleGroupController::class . ':updateMuscleGroup');
    $group->delete('/{id}', App\Controllers\MuscleGroupController::class . ':deleteMuscleGroup');
});

// Global OPTIONS handler (préflight CORS)
$app->options('/{routes:.+}', function ($request, $response) {
    return $response;
});

$app->run();
