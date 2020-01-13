<?php

namespace App\Traits;

use App\Transformers\ProductTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
    /**
     * @param $data
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    private function successResponse($data, $code)
    {
        $data['status'] = 'success';
        $data['code'] = $code;
        return response()->json($data, $code);
    }

    /**
     * @param $message
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, $code)
    {
        return response()->json([
            'status'    => 'error',
            'message'   => $message,
            'code'      => $code
        ],$code);
    }

    protected function errorUpdateNoChanges()
    {
        $msg = 'Must supply at least one different value to update';
        $code = 422;    // Unprocessable Entity (malformed petition)
        return $this->errorResponse($msg, $code);
    }

    /**
     * @param $data
     * @param $transformer
     */
    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }

    /**
     * @param Collection $collection
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty())
        {
            return $this->successResponse([
                'data' => $collection
            ], $code);
        }
        $transformer = $collection->first()->transformer;

        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortData($collection, $transformer);

        // transform after sort because transform does not retorn a laravel collection
        $collection = $this->transformData($collection, $transformer);
// we don't use 'data' anymore because fractal already return
// the transformation inside 'data' element
//        return $this->successResponse([
//            'data' => $collection
//        ], $code);
        return $this->successResponse($collection, $code);
    }

    /**
     * @param Collection $collection
     * @param UserTransformer|ProductTransformer $transformer
     *          ... u other transformers class
     * @return Collection|mixed
     */
    protected function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by') && $collection->isNotEmpty())
        {
            $sortBy = $transformer::originalAttribute(request()->sort_by);
            if ($sortBy)
            {
                // $collection = $collection->sortBy($sortBy);
                // or new from Laravel 5.4
                $collection = $collection->sortBy->{$sortBy};
            }
        }
        return $collection;
    }

    /**
     * @param Collection $collection
     * @param UserTransformer|ProductTransformer $transformer
     *          ... u other transformers class
     * @return Collection|mixed
     */
    protected function filterData(Collection $collection, $transformer)
    {
        if ($collection->isNotEmpty())
        {
            foreach(request()->query() as $query => $value)
            {
                $filterBy = $transformer::originalAttribute($query);
                if ($filterBy && $value)
                {
                    // filter by equal
                    $collection = $collection->where($filterBy, $value);
                }
            }
        }
        return $collection;
    }

    /**
     * @param Model $instance
     * @param int $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showOne(Model $instance, $code = 200)
    {
        if (is_null($instance))
        {
            return $this->successResponse([
                'data' => $instance
            ], $code);
        }
// we don't use 'data' anymore because fractal already return
// the transformation inside 'data' element
//        return $this->successResponse([
//            'data' => $instance
//        ], $code);
        $transformer = $instance->transformer;
        $instance = $this->transformData($instance, $transformer);
        return $this->successResponse($instance, $code);
    }

    /**
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse([
            'data' => (string)$message
        ], $code);
    }

}
