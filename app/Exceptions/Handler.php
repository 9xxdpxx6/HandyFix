<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $this->reportable(function (Throwable $e) {
            //
        });

        // Обработка 404 ошибок
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('dashboard*')) {
                return response()->view('dashboard.errors.404', [], 404);
            }
        });

        // Обработка 403 ошибок
        $this->renderable(function (AuthorizationException $e, Request $request) {
            if ($request->is('dashboard*')) {
                return response()->view('dashboard.errors.403', [], 403);
            }
        });

        // Обработка 500 и других ошибок
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('dashboard*')) {
                if ($e instanceof HttpException) {
                    $code = $e->getStatusCode();
                    if (view()->exists("dashboard.errors.{$code}")) {
                        return response()->view("dashboard.errors.{$code}", [], $code);
                    }
                }
                
                if (config('app.debug') === false) {
                    return response()->view('dashboard.errors.500', [], 500);
                }
            }
        });
    }
}
