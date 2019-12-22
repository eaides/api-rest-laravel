<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
            'description' => [],
            'image' => ['string', 'max:255'],
            'validation_token' => ['string', 'max:100'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'role' => User::ROLE_READER,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'validation_token' => null,
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
        $disable_web_routes = config('app.disable_web_routes');
        $pattern =  config('app.api_prefix') . '/*';
        if ($disable_web_routes || $request->is($pattern)) {
            $this->validator($request->all())->validate();

            $user = $this->create($request->all());

            $use_email_verification = config('app.use_email_verification');
            if (!$use_email_verification)
            {
                $user->validation_token = null;
                $user->email_verified_at = now();
            }
            else
            {
                $user->validation_token = User::generateVerificationToken();
                $user->email_verified_at = null;
            }
            $user->save();

            return $user;
        }
        return $this->traitRegister($request);
    }

}
