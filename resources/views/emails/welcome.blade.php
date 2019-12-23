<p>Hello {{ $user->name }} {{$user->surname }}</p>

<p>Thank for register, please validate your email by following the next link:</p>

<a href="{{ route('api.verify', $user->validation_token) }}">{{ route('api.verify', $user->validation_token) }}</a>

<p>Admin -- api-rest-laravel.com.dev</p>