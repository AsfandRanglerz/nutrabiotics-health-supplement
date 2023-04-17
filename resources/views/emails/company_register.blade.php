@component('mail::message')
<h1 style="margin:0 auto 10px;width:145px">Registration</h1>

<p>Dear {{ $message['name'] }},</p>
<p>Thank you for registering your pharmacy with us. We have received the following information:</p>

<p><strong>Email:</strong> {{ $message['email'] }}</p>

<p>We will review your registration.</p>


Thanks,<br>
{{ config('app.name') }}
@endcomponent

