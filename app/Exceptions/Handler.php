<?php

namespace App\Exceptions;

use Throwable;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException; 
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
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
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $exception, $request) {
            // ? Validation response for both Development and Production environments
            if($exception instanceof ValidationException) {
                return $this->invalidJson($request, $exception);
            }

            if (config('app.debug')) {
                if ($exception instanceof NotFoundHttpException) {
                    return response([
                        "message" => "Not Found.",
                        "errors" => [
                            "url" => [
                                "{$request->fullUrl()} is invalid."
                            ]
                        ],
                    ], Response::HTTP_NOT_FOUND);
                }

                if ($exception instanceof MethodNotAllowedHttpException) {
                    return response([
                        "message" => "Method not allowed.",
                        "errors" => [
                            "url" => [
                                "The {$request->method()} method is not supported for {$request->fullUrl()}"
                            ]
                        ],
                    ], Response::HTTP_METHOD_NOT_ALLOWED);
                }

                if ($exception instanceof AuthorizationException) {
                    return response([
                        "message" => "Not authorized.",
                        "errors" => [
                            'user' => [
                                "{$exception->getMessage()}"
                            ]
                        ],
                    ], Response::HTTP_FORBIDDEN);
                }
        
                if ($exception instanceof HttpException) {
                    return response([
                        "message" => "Invalid.",
                        "errors" => [
                            'reason' => [
                                "{$exception->getMessage()}"
                            ]
                        ],
                    ], $exception->getStatusCode());
                }
                
                // Handle all other exception types with Generic error message
                return $this->genericException(); 
            } else {
                return $this->genericException();
            }
        });
    }

    private function genericException()
    {
        return response([
            "message" => "Internal server error.",
            "errors" => [
                'server' => [
                    "Please try again or contact Support."
                ]
            ],
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
