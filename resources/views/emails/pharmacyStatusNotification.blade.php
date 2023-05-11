@component('mail::message')
<h1 style="margin: 0 auto 10px; width: 145px;">Registration Status</h1>

<p>Dear {{ $status['name'] }},</p>

@if($status['is_active'] == 'activated')
<p>Your account has been activated and you now have access to login to your pharmacy account.</p>

<p>Thank you for choosing us as your partner in Nutrabiotics. We look forward to serving you.</p>

@else
<p>Your account has been deactivated and you no longer have access to login to your pharmacy account.</p>
@endif


Thanks,<br>
{{ config('app.name') }}
@endcomponent



