<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Support\ApiResponse;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if ($request->is('api/*')) {
            return null; // 🔥 impede redirect
        }

        return route('login');
    }

    protected function unauthenticated($request, array $guards)
    {
        if ($request->is('api/*')) {
            abort(response()->json([
                'success' => false,
                'message' => 'Não autenticado',
                'errors' => null,
            ], 401));
        }

        parent::unauthenticated($request, $guards);
    }
}
