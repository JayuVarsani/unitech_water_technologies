<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Utility\Exceptions\ApiException;
use App\Utility\Response\ApiErrorResponse;
use App\Utility\Response\ApiValidationFailResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {});
    }

    public function render($request, Throwable $e): ApiValidationFailResponse|ApiErrorResponse|Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|RedirectResponse
    {

        if ((app()->isProduction() && $request->hasHeader('x-livewire')) || $request->expectsJson()) {
            if ($e instanceof ValidationException) {
                return new ApiValidationFailResponse($e);
            } elseif ($e instanceof ApiException) {
                return new ApiErrorResponse($e->getMessage(), 412);
            } elseif ($e instanceof AuthenticationException) {
                return new ApiErrorResponse($e->getMessage(), 401);
            } elseif ($e instanceof MethodNotAllowedHttpException) {
                return new ApiErrorResponse($e->getMessage(), 412);
            } elseif ($e instanceof NotFoundHttpException) {
                return new ApiErrorResponse($e->getMessage(), 412);
            } elseif ($e instanceof MissingAbilityException) {
                return new ApiErrorResponse(__('app.login_required'), 401);
            } elseif ($e instanceof ModelNotFoundException) {
                return new ApiErrorResponse($e->getMessage(), 412);
            }
            if (! app()->isLocal()) {
                return new ApiErrorResponse(__('app.internal_server_error'), 500);
            }
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            abort(403, $e->getMessage());
        }

        return parent::render($request, $e);
    }
}
