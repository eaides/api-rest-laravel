<p>Hello {{ $user->name }} {{$user->surname }}</p>

<p>Your change your email, please confirm it by following the next link:</p>

<a href="{{ route('api.verify', $user->validation_token ) }}">{{ route('api.verify', $user->validation_token ) }}</a>

<p>Admin -- api-rest-laravel.com.dev</p>