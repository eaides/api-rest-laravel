<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInputs = [];

        foreach($request->request->all() as $input => $value)
        {
            $transformedInputs[$transformer::originalAttribute($input)] = $value;
        }
        $request->replace($transformedInputs);

        $response = $next($request);

        if (isset($response->exception)
            && $response->exception instanceof ValidationException)
        {
            $data = $response->getData();

            $transformedError = [];

            foreach($data->message as $field => $error)
            {
                $transformedField = $transformer::transformedAttribute($field);

                if (is_null($transformedField))
                {
                    $transformedField = $field;
                }

                $transformedError[$transformedField] = str_replace($field, $transformedField, $error);
            }

            $data->message = $transformedError;

            $response->setData($data);
        }

        return $response;
    }
}
