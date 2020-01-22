<?php

namespace App\Http\Controllers\User;

use App\Helpers\Helper;
use App\Mail\UserCreated;
use App\Traits\ApiResponser;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Storage;
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

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'.UserTransformer::class)
            ->only(['store','update']);
    }

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
     * This method is implemented and called in the parent RegisterController
     * The method is excepted on API routes declaration,
     * never use this method directly
     *
     * @param  array|null $data
     * @return \App\User|\Illuminate\Http\JsonResponse
     */
    public function create($data=null, $request = null)
    {
        if (!$data || !is_array($data))
        {
            return $this->errorResponse('No data received', 405);
        }
        return parent::create($data, $request);
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
            return $this->showOne($register, 201);
        }
        return $this->errorResponse('Can not create new User', 500);
    }

    /**
     * Display the specified resource.
     *
     * deprecated @param  int  $id
     * @param  User $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        /* can receive an $id and search with findOrFail

         * $user = User::findOrFail($id);
         *
         * or can refactor the method to receive (as implicit injection)
         *      the desired model
         * In that case the name of the parameter must be the same
         * as the name passed by the route.
         * Can check with artisan route:list
         */
//        $user = User::findOrFail($id);

        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * deprecated @param  int  $id
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, User $user)
    {
        // /** @var User $user */
        // $user = User::findOrFail($id);

        $rules = [
            'name' => ['string', 'max:50'],
            'surname' => ['string', 'max:100'],
            'role' => ['in:'.User::ROLE_PUBLISHER.','.User::ROLE_READER.','.USER::ROLE_ADMIN,],
            'email' => [
                'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'image' => [
                'image',
                Rule::dimensions()->maxWidth(2000)->maxHeight(2000),
                'max:10240',
            ],
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
            /*
             * @todo: check if the authenticated user can change this (is admin)
             *      401 = Unauthorized
             * return $this->errorResponse('The authenticated user has not privileges to change the role of an user', 401);
             */

            /* the user must be verified */
            if (is_null($user->email_verified_at))
            {
                /** 409 - Conflict */
                return $this->errorResponse('The role can be change only for verified users', 409);
            }
            $user->role = $request->role;
        }

        if ($request->has('email') && $user->email != $request->email)
        {
            if (Helper::needApiValidation())
            {
                $user->verification_token = User::generateVerificationToken();
                $user->email_verified_at = null;
                $minutes = intval(User::MINUTES_TO_RESEND);
                $user->next_resend_at = Carbon::now()->addMinutes($minutes);
            } else {
                $user->next_resend_at = null;
            }
            $user->email = $request->email;
        }

        if ($request->has('bio'))
        {
            $user->bio = $request->bio;
        }

        if ($user->isVerified() && $request->hasFile('image'))
        {
            Storage::delete($user->image);

            $user->image = Helper::storeAndReSizeImg($request, 'image');
        }

        if (!$user->isDirty())
        {
            return $this->errorUpdateNoChanges();
        }

        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * deprecated @param  int  $id
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        /*
        *  @todo: check if the authenticated user can change this (is admin)
        *       401 = Unauthorized
        * return $this->errorResponse('The authenticated user has not privileges to delete other users', 401);
        */

        /*
        *  @todo: check if the authenticated user is the same user to be eliminated
        *       409 - Conflict
        * return $this->errorResponse('The authenticated user can not be deleted (don't try suicide)', 409);
        */

        // @todo must remove only for permanently remove
        Storage::delete($user->image);

        $user->delete();

        return $this->showOne($user);
    }

    /**
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verification_token = null;
        $user->email_verified_at = now();
        $user->next_resend_at = null;

        $user->save();

        return $this->showMessage('account verified succesfully');
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function resend(User $user)
    {
        if ($user->isVerified())
        {
            return $this->errorResponse('This user is already verified', 409);
        }
        if (! is_null($user->next_resend_at))
        {
            if (Carbon::now() < $user->next_resend_at)
            {
                return $this->errorResponse('Mail already send, check your spam or wait some minutes until new request', 409);
            }

        }

        $minutes = intval(User::MINUTES_TO_RESEND);
        $user->next_resend_at = Carbon::now()->addMinutes($minutes);
        $user->save();

        retry(5, function() use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('validation email was resend');
    }
}
