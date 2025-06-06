<?php
use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../vendor/autoload.php';

// Load .env
Dotenv::createImmutable(__DIR__ . '/../')->load();

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions(__DIR__ . '/../src/dependencies.php');

$container = $containerBuilder->build();

AppFactory::setContainer($container);

// Create App
$app = AppFactory::create();

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

if ($container->has('errorHandler')) {
    $app->addErrorMiddleware(true, true, true)
        ->setDefaultErrorHandler($container->get('errorHandler'));
} else {
    $app->addErrorMiddleware(true, true, true);
}

$app->get('/', function ($request, $response) {
    $response->getBody()->write(json_encode(['message' => 'API Slim opérationnelle !']));
    return $response->withHeader('Content-Type', 'application/json');
});

// Routes...
$app->group('/users', function (RouteCollectorProxy $group) {
    $group->post('', App\Controllers\UserController::class . ':createUser');
    $group->get('', App\Controllers\UserController::class . ':getAllUsers');
    $group->get('/{id}', App\Controllers\UserController::class . ':getUser');
    $group->put('/{id}', App\Controllers\UserController::class . ':updateUser');
    $group->delete('/{id}', App\Controllers\UserController::class . ':deleteUser');
    $group->post('/login', App\Controllers\AuthController::class . ':login');
    $group->post('/register', App\Controllers\AuthController::class . ':register');
});

$app->group('/exercises', function (RouteCollectorProxy $group) {
    $group->get('', App\Controllers\ExerciseController::class . ':getAllExercises');
    $group->post('', App\Controllers\ExerciseController::class . ':createExercise');
    $group->get('/{id}', App\Controllers\ExerciseController::class . ':getExercise');
    $group->put('/{id}', App\Controllers\ExerciseController::class . ':updateExercise');
    $group->delete('/{id}', App\Controllers\ExerciseController::class . ':deleteExercise');
});

$app->group('/sets', function (RouteCollectorProxy $group) {
    $group->get('', App\Controllers\SetController::class . ':getAllSets');
    $group->post('', App\Controllers\SetController::class . ':createSet');
    $group->get('/{id}', App\Controllers\SetController::class . ':getSet');
    $group->put('/{id}', App\Controllers\SetController::class . ':updateSet');
    $group->delete('/{id}', App\Controllers\SetController::class . ':deleteSet');
});

$app->group('/templates', function (RouteCollectorProxy $group) {
    $group->get('', App\Controllers\TemplateController::class . ':getAllTemplates');
    $group->post('', App\Controllers\TemplateController::class . ':createTemplate');
    $group->get('/{id}', App\Controllers\TemplateController::class . ':getTemplate');
    $group->put('/{id}', callable :App\Controllers\TemplateController::class . ':updateTemplate');
    $group->delete('/{id}', App\Controllers\TemplateController::class . ':deleteTemplate');
});

$app->get('/users/{id}/templates', App\Controllers\TemplateController::class . ':getTemplateByUserId');

$app->group('/muscle-groups', function (RouteCollectorProxy $group) {
    $group->get('', App\Controllers\MuscleGroupController::class . ':getAllMuscleGroups');
    $group->post('', App\Controllers\MuscleGroupController::class . ':createMuscleGroup');
    $group->get('/{id}', App\Controllers\MuscleGroupController::class . ':getMuscleGroup');
    $group->put('/{id}', App\Controllers\MuscleGroupController::class . ':updateMuscleGroup');
    $group->delete('/{id}', App\Controllers\MuscleGroupController::class . ':deleteMuscleGroup');
});

// Run App
$app->run();
