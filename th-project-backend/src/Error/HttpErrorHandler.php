<?php
namespace App\Error;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpException;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

class HttpErrorHandler implements ErrorHandlerInterface
{
    private $responseFactory;
    private $logger;

    public function __construct(ResponseFactoryInterface $responseFactory, LoggerInterface $logger = null)
    {
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
    }

    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        if ($this->logger && $logErrors) {
            $this->logger->error($exception->getMessage());
        }

        $statusCode = $exception instanceof HttpException ? $exception->getCode() : 500;

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write(json_encode([
            'error' => $displayErrorDetails ? $exception->getMessage() : 'Internal Server Error'
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }
}
