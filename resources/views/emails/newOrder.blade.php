@component('mail::message')
<h1 style="margin: 0 auto 10px; width: 145px;">New Order</h1>

<p>Dear {{ $message['pharmacy_name'] }},</p>

<p>You have received a new order from "{{ $message['user_name'] }}" with the following details:</p>

<table style="font-size: 14px; border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Product</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Quantity</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Price</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $message['product_name'] }}</td>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $message['quantity'] }}</td>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $message['price'] }}</td>
            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $message['sub_total'] }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid #ddd; padding: 8px;" colspan="3"></td>
            <td style="border: 1px solid #ddd; padding: 8px">
                <strong>Discount:</strong> {{ $message['d_per'] }}%<br>
                <strong>Total:</strong> {{ ceil($message['total']) }}</td>
        </tr>
    </tbody>

</table>

<p style="font-size: 16px;">The order code is <strong>{{ $message['code'] }}</strong>.</p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
