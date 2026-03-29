<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ApiResponse
{
    /**
     * Resposta de sucesso padrão
     */
    public static function success(
        mixed $data = null,
        string $message = 'OK',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Resposta de erro padrão
     */
    public static function error(
        string $message = 'Erro',
        int $status = 400,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    /**
     * Resposta de paginação padrão
     */
    public static function paginated(
        LengthAwarePaginator $paginator,
        string $message = 'OK'
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * Resposta sem conteúdo (204)
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }
}
