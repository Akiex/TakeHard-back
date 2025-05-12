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

    public function getAllTemplates(Request $request, Response $response): Response
    {
        $templates = $this->templateManager->getAllTemplates();
        $response->getBody()->write(json_encode($templates));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getTemplate(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $template = $this->templateManager->getTemplateById($id);
        if (!$template) {
            $payload = json_encode(['message' => 'Template non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }
        $response->getBody()->write(json_encode($template));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createTemplate(Request $request, Response $response): Response
    {
        $data = (array)$request->getParsedBody();
        if (empty($data['name']) || empty($data['description'])) {
            $payload = json_encode(['message' => 'Champs requis manquants']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        $template = new Template($data);
        $this->templateManager->createTemplate($template);

        $payload = json_encode(['message' => 'Template créé avec succès']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function updateTemplate(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $existingTemplate = $this->templateManager->getTemplateById($id);
        if (!$existingTemplate) {
            $payload = json_encode(['message' => 'Template non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $data = (array)$request->getParsedBody();
        $existingTemplate->setName($data['name'] ?? $existingTemplate->getName());
        $existingTemplate->setDescription($data['description'] ?? $existingTemplate->getDescription());

        $this->templateManager->updateTemplate($existingTemplate);

        $payload = json_encode(['message' => 'Template mis à jour']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteTemplate(Request $request, Response $response, array $args): Response
    {
        $id = (int)$args['id'];
        $existingTemplate = $this->templateManager->getTemplateById($id);
        if (!$existingTemplate) {
            $payload = json_encode(['message' => 'Template non trouvé']);
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

        $this->templateManager->deleteTemplate($id);
        $payload = json_encode(['message' => 'Template supprimé avec succès']);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
