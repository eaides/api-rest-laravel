@component('mail::message')
# Hello {{ $user->name }} {{$user->surname }}

Thank for register, please validate your email by following the next button:

@component('mail::button', ['url' => route('api.verify', $user->verification_token)])
Confirm my account
@endcomponent

Thanks,<br>
Admin -- {{ config('app.name') }}
@endcomponent
