<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\Helpers\Helper;
use App\Traits\ApiResponser;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers
    {
        register as public traitRegister;
    }
    use ApiResponser;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'surname' => ['required', 'string', 'max:100'],
            'role' => ['string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'image' => [
                'image',
                Rule::dimensions()->maxWidth(2000)->maxHeight(2000),
                'max:10240',
            ],
            'verification_token' => ['string', 'max:200'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data, $request=null)
    {
        if (!array_key_exists('verification_token', $data))
        {
            $data['verification_token'] = null;
        }

        $data['image'] = null;
        if (($request instanceof Request)
            && $request->hasFile('image'))
        {
            $data['image'] = Helper::storeAndReSizeImg($request, 'image');
        }

        if (!array_key_exists('bio', $data))
        {
            $data['bio'] = '';
        }

        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'role' => User::ROLE_READER,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verification_token' => $data['verification_token'],
            'image' => $data['image'],
            'bio' => $data['bio'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  Request  $request
     * @return User|\Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if (Helper::isApiRequest()) {
            $this->validator($request->all())->validate();

            $data = $request->all();
            if (Helper::needApiValidation())
            {
                $data['verification_token']   = User::generateVerificationToken();
                $data['email_verified_at']  = null;
            }
            else
            {
                $data['verification_token']   = null;
                $data['email_verified_at']  = now();
            }
            $user = $this->create($data, $request);

            return $user;
        }
        return $this->traitRegister($request);
    }

}
