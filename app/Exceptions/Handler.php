<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
     * @return void
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
     */
    public function render($request, Exception $exception)
    {
        $disable_web_routes = config('app.disable_web_routes');
        $pattern =  config('app.api_prefix') . '/*';
        if ($disable_web_routes || $request->is($pattern)) {
            if ($exception instanceof ValidationException)
            {
                return $this->convertValidationExceptionToResponse($exception, $request);
            }
            if ($exception instanceof ModelNotFoundException)
            {
                $model = strtolower(class_basename($exception->getModel()));
                return $this->errorResponse("Model of type {$model} does not exists for the specified id", 404);
            }

            // authentication todo

            // authorization todo

            if ($exception instanceof NotFoundHttpException)
            {
                return $this->errorResponse("The specified URL is invalid", 404);
            }

        }
        return parent::render($request, $exception);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $disable_web_routes = config('app.disable_web_routes');
        $pattern =  config('app.api_prefix') . '/*';
        if ($disable_web_routes || $request->is($pattern))
        {
            $errors = $e->validator->errors()->getMessages();
            return $this->errorResponse($errors, 422);
        }
        return parent::convertValidationExceptionToResponse($e, $request);
    }

}
