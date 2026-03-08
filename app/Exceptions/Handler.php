<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        $statusCode = $this->resolveStatusCode($e);
        $message = $this->resolveErrorMessage($e, $statusCode);

        if ($request->expectsJson()) {
            return response()->json([
                'error' => [
                    'code' => $statusCode,
                    'message' => $message,
                ],
            ], $statusCode);
        }

        if (view()->exists('errors.generic')) {
            return response()->view('errors.generic', [
                'statusCode' => $statusCode,
                'message' => $message,
            ], $statusCode);
        }

        return parent::render($request, $e);
    }

    /**
     * Resolve the appropriate HTTP status code for an exception.
     */
    private function resolveStatusCode(Throwable $e): int
    {
        if ($e instanceof HttpExceptionInterface) {
            return $e->getStatusCode();
        }

        if ($e instanceof NotFoundHttpException) {
            return SymfonyResponse::HTTP_NOT_FOUND;
        }

        if ($e instanceof UnauthorizedHttpException || $e instanceof AuthenticationException) {
            return SymfonyResponse::HTTP_UNAUTHORIZED;
        }

        if ($e instanceof AuthorizationException) {
            return SymfonyResponse::HTTP_FORBIDDEN;
        }

        if ($e instanceof TokenMismatchException) {
            return SymfonyResponse::HTTP_PAGE_EXPIRED;
        }

        if ($e instanceof ValidationException) {
            return SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY;
        }

        if ($e instanceof ThrottleRequestsException) {
            return SymfonyResponse::HTTP_TOO_MANY_REQUESTS;
        }

        return SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;
    }

    /**
     * Resolve a safe, user-facing error message.
     */
    private function resolveErrorMessage(Throwable $e, int $statusCode): string
    {
        if ($e instanceof HttpExceptionInterface && !empty($e->getMessage())) {
            return $e->getMessage();
        }

        if ($e instanceof ValidationException) {
            return $e->getMessage() ?: 'Validation failed.';
        }

        return SymfonyResponse::$statusTexts[$statusCode] ?? 'An unexpected error occurred.';
    }
}
