<?php
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = AppFactory::create();

// Chargement des dépendances (PDO, managers, controllers…)
(require __DIR__ . '/../src/dependencies.php')($app);

// Middleware JSON responses
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

// Example de route
$app->get('/', function ($req, $res) {
    $res->getBody()->write('API Slim opérationnelle !');
    return $res;
});

// Routes Utilisateur et Auth
$app->group('/users', function (RouteCollectorProxy $group) {
    $group->post('',        \App\Controllers\UserController::class . ':createUser');
    $group->get('',         \App\Controllers\UserController::class . ':getAllUsers');
    $group->get('/{id}',    \App\Controllers\UserController::class . ':getUser');
    $group->put('/{id}',    \App\Controllers\UserController::class . ':updateUser');
    $group->delete('/{id}', \App\Controllers\UserController::class . ':deleteUser');
    $group->post('/login',  \App\Controllers\AuthController::class . ':login');
    $group->post('/register', \App\Controllers\AuthController::class . ':register');
});

// Routes Exercise
$app->group('/exercises', function (RouteCollectorProxy $group) {
    $group->get('',         \App\Controllers\ExerciseController::class . ':getAllExercises');
    $group->post('',        \App\Controllers\ExerciseController::class . ':createExercise');
    $group->get('/{id}',    \App\Controllers\ExerciseController::class . ':getExercise');
    $group->put('/{id}',    \App\Controllers\ExerciseController::class . ':updateExercise');
    $group->delete('/{id}', \App\Controllers\ExerciseController::class . ':deleteExercise');
});

// Routes Set
$app->group('/sets', function (RouteCollectorProxy $group) {
    $group->get('',         \App\Controllers\SetController::class . ':getAllSets');
    $group->post('',        \App\Controllers\SetController::class . ':createSet');
    $group->get('/{id}',    \App\Controllers\SetController::class . ':getSet');
    $group->put('/{id}',    \App\Controllers\SetController::class . ':updateSet');
    $group->delete('/{id}', \App\Controllers\SetController::class . ':deleteSet');
});

// Routes Template
$app->group('/templates', function (RouteCollectorProxy $group) {
    $group->get('',         \App\Controllers\TemplateController::class . ':getAllTemplates');
    $group->post('',        \App\Controllers\TemplateController::class . ':createTemplate');
    $group->get('/{id}',    \App\Controllers\TemplateController::class . ':getTemplate');
    $group->put('/{id}',    \App\Controllers\TemplateController::class . ':updateTemplate');
    $group->delete('/{id}', \App\Controllers\TemplateController::class . ':deleteTemplate');
});

// Routes MuscleGroup
$app->group('/muscle-groups', function (RouteCollectorProxy $group) {
    $group->get('',         \App\Controllers\MuscleGroupController::class . ':getAllMuscleGroups');
    $group->post('',        \App\Controllers\MuscleGroupController::class . ':createMuscleGroup');
    $group->get('/{id}',    \App\Controllers\MuscleGroupController::class . ':getMuscleGroup');
    $group->put('/{id}',    \App\Controllers\MuscleGroupController::class . ':updateMuscleGroup');
    $group->delete('/{id}', \App\Controllers\MuscleGroupController::class . ':deleteMuscleGroup');
});

$app->run();