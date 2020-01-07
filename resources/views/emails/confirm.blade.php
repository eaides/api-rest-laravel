@component('mail::message')
# Hello {{ $user->name }} {{$user->surname }}

Your change your email, please confirm it by following the next button:

@component('mail::button', ['url' => route('api.verify', $user->verification_token )])
Confirm my new email
@endcomponent

Thanks,<br>
Admin -- {{ config('app.name') }}
@endcomponent
