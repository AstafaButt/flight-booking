<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Failed</title>
</head>
<body>
    <h2>Hello {{ $booking->first_name }},</h2>

    <p>Unfortunately, your recent payment attempt for booking <strong>#{{ $booking->id }}</strong> was <span style="color:red;">unsuccessful</span>.</p>

    <h3>Details:</h3>
    <ul>
        <li><strong>Status:</strong> {{ ucfirst($booking->status) }}</li>
        <li><strong>Amount:</strong> {{ $booking->amount }} INR</li>
    </ul>

    <p>If this was unexpected, please try again with a different payment method or contact our support team at <strong>support@youragency.com</strong>.</p>

    <br>
    <p>Regards,<br>
    Travel Agency Team</p>
</body>
</html>
