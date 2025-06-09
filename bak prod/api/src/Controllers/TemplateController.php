<?php
namespace App\Controllers;

use App\Managers\TemplateManager;
use App\Models\Template;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class TemplateController
{
    private TemplateManager $templateManager;

    public function __construct(TemplateManager $templateManager)
    {
        $this->templateManager = $templateManager;
    }

    // GET /templates
    public function getAllTemplates(Request $request, Response $response): Response
    {
        $templates = $this->templateManager->getAllTemplates();
        $payload = array_map(function(Template $t) {
            return [
                'id'          => $t->getId(),
                'user_id'     => $t->getUserId(),
                'name'        => $t->getName(),
                'description' => $t->getDescription(),
                'is_public'   => $t->getIsPublic(),
                'sets'        => array_map(function($s) {
                    return [
                        'id'         => $s->getId(),
                        'sets'       => $s->getSets(),
                        'reps'       => $s->getReps(),
                        'weight'     => $s->getWeight(),
                        'rest_time'  => $s->getRestTime(),
                        'exercises'  => array_map(fn($ex) => [
                            'id'   => $ex->getId(),
                            'name' => $ex->getName(),
                        ], $s->getExercises()),
                    ];
                }, $t->getSets()),
            ];
        }, $templates);

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // GET /templates/{id}
    public function getTemplate(Request $request, Response $response, array $args): Response
    {
        $template = $this->templateManager->getTemplateById((int)$args['id']);
        if (!$template) {
            $response->getBody()->write(json_encode(['message' => 'Template non trouvé']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $data = [
            'id'          => $template->getId(),
            'user_id'     => $template->getUserId(),
            'name'        => $template->getName(),
            'description' => $template->getDescription(),
            'is_public'   => $template->getIsPublic(),
            'sets'        => array_map(function($s) {
                return [
                    'id'         => $s->getId(),
                    'sets'       => $s->getSets(),
                    'reps'       => $s->getReps(),
                    'weight'     => $s->getWeight(),
                    'rest_time'  => $s->getRestTime(),
                    'exercises'  => array_map(fn($ex) => [
                        'id'   => $ex->getId(),
                        'name' => $ex->getName(),
                    ], $s->getExercises()),
                ];
            }, $template->getSets()),
        ];

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // POST /templates
    public function createTemplate(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        $template = $this->templateManager->createTemplate($data);
        $response->getBody()->write(json_encode([
            'id'          => $template->getId(),
            'user_id'     => $template->getUserId(),
            'name'        => $template->getName(),
            'description' => $template->getDescription(),
            'is_public'   => $template->getIsPublic(),
            'sets'        => array_map(function($s) {
                return [
                    'id'         => $s->getId(),
                    'sets'       => $s->getSets(),
                    'reps'       => $s->getReps(),
                    'weight'     => $s->getWeight(),
                    'rest_time'  => $s->getRestTime(),
                    'exercises'  => array_map(fn($ex) => [
                        'id'   => $ex->getId(),
                        'name' => $ex->getName(),
                    ], $s->getExercises()),
                ];
            }, $template->getSets()),
        ]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    // PUT /templates/{id}
    public function updateTemplate(Request $request, Response $response, array $args): Response
    {
        $data = (array)$request->getParsedBody();
        $template = $this->templateManager->updateTemplate((int)$args['id'], $data);
        if (!$template) {
            $response->getBody()->write(json_encode(['message' => 'Template non trouvé']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $response->getBody()->write(json_encode([
            'id'          => $template->getId(),
            'user_id'     => $template->getUserId(),
            'name'        => $template->getName(),
            'description' => $template->getDescription(),
            'is_public'   => $template->getIsPublic(),
            'sets'        => array_map(function($s) {
                return [
                    'id'         => $s->getId(),
                    'sets'       => $s->getSets(),
                    'reps'       => $s->getReps(),
                    'weight'     => $s->getWeight(),
                    'rest_time'  => $s->getRestTime(),
                    'exercises'  => array_map(fn($ex) => [
                        'id'   => $ex->getId(),
                        'name' => $ex->getName(),
                    ], $s->getExercises()),
                ];
            }, $template->getSets()),
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function getTemplateByUserId(Request $request, Response $response, array $args): Response
    {
        $userId = (int)$args['id'];
        $templates = $this->templateManager->getTemplateByUserId($userId);

        $payload = array_map(function(Template $t) {
            return [
                'id'          => $t->getId(),
                'user_id'     => $t->getUserId(),
                'name'        => $t->getName(),
                'description' => $t->getDescription(),
                'is_public'   => $t->getIsPublic(),
                'sets'        => array_map(function($s) {
                    return [
                        'id'         => $s->getId(),
                        'sets'       => $s->getSets(),
                        'reps'       => $s->getReps(),
                        'weight'     => $s->getWeight(),
                        'rest_time'  => $s->getRestTime(),
                        'exercises'  => array_map(fn($ex) => [
                            'id'   => $ex->getId(),
                            'name' => $ex->getName(),
                        ], $s->getExercises()),
                    ];
                }, $t->getSets()),
            ];
        }, $templates);

        $response->getBody()->write(json_encode($payload));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // DELETE /templates/{id}
    public function deleteTemplate(Request $request, Response $response, array $args): Response
    {
        $success = $this->templateManager->deleteTemplate((int)$args['id']);
        if (!$success) {
            $response->getBody()->write(json_encode(['message' => 'Erreur suppression']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
        }

        $response->getBody()->write(json_encode(['message' => 'Template supprimé avec succès']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
