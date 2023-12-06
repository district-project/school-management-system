@component('mail::message')

Hello {{ $user->name }},

<p>We understand it happens.</p>

@component('mail::button', ['url' => url('reset', ['token' => $user->remember_token])])
Reset Your Password
@endcomponent

<p>In case of any issues recovering your password, please contact us.</p>

Thanks, <br>
{{ config('app.name') }}

@endcomponent
