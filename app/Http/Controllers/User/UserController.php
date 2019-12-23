<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Auth\RegisterController;
use App\User;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $register = parent::register($request);

        if ($register instanceof User)
        {
            return $this->showOne($register, 200);
        }
        return $this->successMessage('user created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);

        $rules = [
            'name' => ['string', 'max:50'],
            'surname' => ['string', 'max:100'],
            'role' => ['in:'.User::ROLE_PUBLISHER.','.User::ROLE_READER.','.USER::ROLE_ADMIN,],
            'email' => ['string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'description' => [],
            'image' => ['string', 'max:255'],
        ];

        $this->validate($request, $rules);

        /* password will be updated in other method */

        if ($request->has('name'))
        {
            $user->name = $request->name;
        }

        if ($request->has('surname'))
        {
            $user->surname = $request->surname;
        }

        if ($request->has('role'))
        {
            // todo: check if the authenticated user can change this (is admin)

            /* the user must be verified */
            if (is_null($user->email_verified_at))
            {
                return $this->errorResponse('The role can be change only for verified users', 409);
            }
            $user->role = $request->role;
        }

        if ($request->has('email') && $user->email != $request->email)
        {
            if (Helper::needApiValidation())
            {
                $user->validation_token = User::generateVerificationToken();
                $user->email_verified_at = null;
            }
            $user->email = $request->email;
        }

        if ($request->has('surname'))
        {
            $user->description = $request->description;
        }

        if ($request->has('image'))
        {
            $user->image = $request->image;
        }

        if (!$user->isDirty())
        {
            return $this->errorResponse('At least one value must change in order to edit the user', 422);
        }

        $user->save();

        return $this->showOne($user);
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

    public function verify($token)
    {

        $user = User::where('validation_token', $token)->firstOrFail();

        $user->validation_token = null;
        $user->email_verified_at = now();

        $user->save();

        return $this->showMessage('account verified succesfully');
    }
}
