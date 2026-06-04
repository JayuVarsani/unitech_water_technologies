<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Utility\Enums\StatusEnum;
use App\Utility\Exceptions\ApiException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! StatusEnum::tryFrom($user)->isActive()) {
            throw new ApiException(__('api.invalid_login'), 401);
        }

        return $next($request);
    }
}
