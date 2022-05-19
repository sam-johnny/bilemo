<?php

namespace App\EventListener;

use App\Exception\CustomerInvalidException;
use App\Exception\ForbiddenException;
use App\Exception\ResourceNotFoundException;
use JMS\Serializer\Exception\RuntimeException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $message = $exception->getMessage();

        $response = new Response();
        $response->setContent($message);
        $response = $this->prepareNewResponse($response, $exception, $message);

        $event->setResponse($response);
    }

    /**
     * @param Response $response
     * @param \Exception $exception
     * @param $message
     * @return Response
     */
    public function prepareNewResponse(Response $response, \Exception $exception, $message): Response
    {
        switch (true) {
            case $exception instanceof HttpExceptionInterface:
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());
                $response->setContent(json_encode(['code' => $exception->getStatusCode(), 'message' => $message]));
                break;
            case $exception instanceof RuntimeException:
                $response->setContent(json_encode(['code' => 400, 'message' => $message]));
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                break;
            case $exception instanceof JWTEncodeFailureException:
            case $exception instanceof JWTDecodeFailureException:
                $response->setContent(json_encode(['code' => 401, 'message' => $message]));
                $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
                break;
            case $exception instanceof CustomerInvalidException:
            case $exception instanceof ForbiddenException:
                $response->setContent(json_encode(['code' => 403, 'message' => $message]));
                $response->setStatusCode(Response::HTTP_FORBIDDEN);
                break;
            case $exception instanceof ResourceNotFoundException:
                $response->setContent(json_encode(['code' => 404, 'message' => $message]));
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                break;
            default:
                $response->setContent(json_encode(['code' => 500, 'message' => $message]));
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $response;
    }
}