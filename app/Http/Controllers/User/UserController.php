<?php

namespace App\Http\Controllers\User;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Auth\RegisterController;
use App\User;

//

/**
 * Class UserController
 *
 * can not extend from ApiController, it's an special case
 * need functionality from RegisterController
 *
 * @package App\Http\Controllers
 */
class UserController extends RegisterController
{
    use ApiResponser;

    protected $empty_response_data = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response('not implemented yet');
    }

    /**
     * Create a new user instance after a valid registration.
     * method implemented in parent RegisterController
     * excepted in API routes declaration, never use directly
     *
     * @param  array|null $data
     * @return \App\User|\Illuminate\Http\JsonResponse
     */
    public function create($data=null)
    {
        if (!$data || !is_array($data))
        {
            return $this->errorResponse('No data received', 405);
        }
        return parent::create($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $register = parent::register($request);
        } catch (ValidationException $exception)
        {
            $data = [
                'status'    => 'error',
                'code'      => 400,
                'message'   => $exception->errors(),
            ];
            // todo use trait functions // bad request 400
            return response()->json($data, 400);
        }
die($register->content());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
