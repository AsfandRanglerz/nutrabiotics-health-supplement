@component('mail::message')
<h1 style="margin: 0 auto 10px; width: 145px;">Registration</h1>

<p>New pharmacy "{{ $admin_message['pharmacy_name'] }}" has been registered. Please review the pharmacy details.</p>

<p>Response to the pharmacy.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent

