@component('mail::message')
<h1 style="margin: 0 auto 10px; width: 145px;">Order Request Form</h1>

<p>Dear {{ $message['admin'] }},</p>

<p>This message is from Pharmacy "{{ $message['pharmacy_name'] }}":</p>

<strong>Description:</strong>
                <p>"{{ $message['description'] }}"</p>

<p style="font-size: 16px;">The order code is <strong>{{ $message['code'] }}</strong>.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
