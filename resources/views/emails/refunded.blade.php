<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Refunded</title>
</head>
<body>
    <h2>Hello {{ $booking->first_name }},</h2>

    <p>Your payment for booking <strong>#{{ $booking->id }}</strong> has been successfully <span style="color:green;">refunded</span>.</p>

    <h3>Refund Details:</h3>
    <ul>
        <li><strong>Amount Refunded:</strong> {{ $booking->amount }} INR</li>
        <li><strong>Status:</strong> {{ ucfirst($booking->status) }}</li>
        <li><strong>Date:</strong> {{ now()->format('d M Y H:i') }}</li>
    </ul>

    <p>Please allow 5–7 business days for the refund to appear in your bank statement, depending on your payment provider.</p>

    <br>
    <p>Thank you for choosing <strong>Travel Agency</strong>.<br>
    Travel Agency Team</p>
</body>
</html>
