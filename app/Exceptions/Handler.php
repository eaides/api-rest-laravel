<?php

namespace App\Exceptions;

use App\Helpers\Helper;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return mixed
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     */
    public function render($request, Exception $exception)
    {
        $disable_own_handler = false;
        $disable_web_routes = config('app.disable_web_routes');
        $pattern =  config('app.api_prefix') . '/*';
        if (
            !$disable_own_handler &&
            ($disable_web_routes || $request->is($pattern)) &&
            !Helper::isFrontend($request)
        ) {
            if ($exception instanceof ValidationException)
            {
                $errors = $exception->validator->errors()->getMessages();
                return $this->errorResponse($errors, 422);
            }
            if ($exception instanceof ModelNotFoundException)
            {
                $model = strtolower(class_basename($exception->getModel()));
                return $this->errorResponse("Model of type {$model} does not exists for the specified id", 404);
            }
            if ($exception instanceof AuthenticationException)
            {
                return $this->unauthenticated($request, $exception);
                // return $this->errorResponse("User Unauthenticated", 401);
            }
            if ($exception instanceof UnauthorizedException)
            {
                return $this->errorResponse("User Unauthorized", 403);
            }
            if ($exception instanceof NotFoundHttpException)
            {
                return $this->errorResponse("The specified URL is invalid", 404);
            }
            if ($exception instanceof MethodNotAllowedHttpException)
            {
                return $this->errorResponse("The HTTP method for the petition is invalid", 405);
            }
            if ($exception instanceof HttpException)
            {
                return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
            }
            if ($exception instanceof QueryException)
            {
                if ($exception->errorInfo[1] == 1451)
                {
                    return $this->errorResponse('Can not delete the resource because exists other resource(s) relational', 409);
                }
            }

            if ($exception instanceof TokenMismatchException)
            {
                return redirect()
                    ->back()
                    ->withInput($request->input());
            }

            // unexpected exception
            if (!config('app.debug'))
            {
                return $this->errorResponse('Unexpected error', 500);
            }
            else
            {
                return $this->errorResponse($exception->getMessage(),500);
            }

        }
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return ($request->expectsJson() || !Helper::isFrontend($request))
            ? $this->errorResponse($exception->getMessage(), 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

}
