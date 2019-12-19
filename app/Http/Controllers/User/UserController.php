<?php

namespace App\Http\Controllers\User;

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
     * @param  array  $data
     * @return \App\User
     */
    public function create($data=null)
    {
        if (!$data || !is_array($data))
        {
            return response()->json($this->empty_response_data, 405);
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
            // todo return json with errors or more complex $data? // bad request 400
            $data = [
                'status'    => 'error',
                'code'      => 400,
                'message'   => $exception->errors(),
            ];
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
     * Show the form for editing the specified resource.
     * excepted in API routes declaration
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * status 405: Method Not Allowed
     */
    public function edit($id)
    {
        return response()->json($this->empty_response_data, 405);
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
