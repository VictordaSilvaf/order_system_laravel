<?php

use App\Support\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /**
         * 🔴 VALIDATION (422)
         */
        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                return ApiResponse::error(
                    'Erro de validação',
                    422,
                    $e->errors()
                );
            }
        });

        /**
         * 🔴 AUTH (401)
         */
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return ApiResponse::error(
                    'Não autenticado',
                    401
                );
            }
        });

        /**
         * 🔴 NOT FOUND (404)
         */
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return ApiResponse::error(
                    'Recurso não encontrado',
                    404
                );
            }
        });

        /**
         * 🔴 HTTP GENERIC (403, 405, etc)
         */
        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            if ($request->is('api/*')) {
                return ApiResponse::error(
                    $e->getMessage() ?: 'Erro HTTP',
                    $e->getStatusCode()
                );
            }
        });

        /**
         * 🔴 FALLBACK (500)
         */
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->is('api/*')) {

                // log centralizado
                report($e);

                $message = app()->isLocal()
                    ? $e->getMessage()
                    : 'Erro interno do servidor';

                return ApiResponse::error($message, 500);
            }
        });
    })->create();
