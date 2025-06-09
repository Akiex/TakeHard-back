<?php
namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Si c’est une requête de type « OPTIONS » (préflight), on renvoie tout de suite la réponse CORS
        if (strtoupper($request->getMethod()) === 'OPTIONS') {
            $response = new Response();
            return $this->addCorsHeaders($response);
        }

        // Sinon, on laisse passer la requête puis on ajoute les headers CORS à la réponse
        $response = $handler->handle($request);
        return $this->addCorsHeaders($response);
    }

    private function addCorsHeaders(ResponseInterface $response): ResponseInterface
    {
        return $response
            // Autoriser l’origine de votre front-end :
            ->withHeader('Access-Control-Allow-Origin', 'https://takehardvantage.42web.io')
            // Autoriser ces méthodes HTTP
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            // Autoriser ces headers dans la requête
            ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
            // Si vous avez besoin d’envoyer des cookies/jwt via Cookie :
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}
