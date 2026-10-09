<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Event Booking</title>
<style>
  body { margin: 0; padding: 0; background-color: #0b0f14; font-family: 'Helvetica Neue', Arial, sans-serif; color: #f2f2f0; line-height: 1.6; }
  .email-wrap { max-width: 600px; margin: 30px auto; background-color: #141c28; border: 1px solid #223043; border-radius: 6px; overflow: hidden; }
  .email-header { background: linear-gradient(135deg, #017b45 0%, #375fac 100%); padding: 25px 30px; border-bottom: 2px solid #66cbe6; }
  .email-header h1 { margin: 0; font-size: 20px; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; }
  .email-body { padding: 30px; }
  .email-body h2 { margin-top: 0; font-size: 18px; color: #ffffff; border-bottom: 1px solid #223043; padding-bottom: 12px; }
  .info-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
  .info-table th { text-align: left; padding: 10px 0; font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 40%; border-bottom: 1px solid #1f2b3c; }
  .info-table td { padding: 10px 0; font-size: 14px; color: #f2f2f0; border-bottom: 1px solid #1f2b3c; }
  .amount-total { font-size: 18px; color: #66cbe6; font-weight: bold; }
  .status-badge { display: inline-block; padding: 3px 8px; font-size: 12px; text-transform: uppercase; font-weight: bold; background-color: #1e293b; color: #66cbe6; border-radius: 3px; }
  .email-footer { background-color: #0f1621; padding: 20px 30px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #223043; }
</style>
</head>
<body>
<div class="email-wrap">
  <div class="email-header">
    <h1>RK Vibes — Event Booking</h1>
  </div>
  <div class="email-body">
    <h2>New Ticket Booking Received</h2>
    <p>A new event booking has been submitted via the website checkout form. Details are provided below:</p>
    
    <table class="info-table">
      <tr>
        <th>Customer Name</th>
        <td><strong>{{ $booking->name }}</strong></td>
      </tr>
      <tr>
        <th>Email Address</th>
        <td><a href="mailto:{{ $booking->email }}" style="color: #66cbe6; text-decoration: none;">{{ $booking->email }}</a></td>
      </tr>
      <tr>
        <th>Phone Number</th>
        <td>{{ $booking->phone ?? 'Not provided' }}</td>
      </tr>
      <tr>
        <th>Ticket Type</th>
        <td>{{ $booking->event_type }}</td>
      </tr>
      <tr>
        <th>Number of Tickets</th>
        <td>{{ $booking->guest_count }}</td>
      </tr>
      <tr>
        <th>Payment Method</th>
        <td><span class="status-badge">{{ strtoupper($booking->payment_method ?? 'card') }}</span></td>
      </tr>
      <tr>
        <th>Total Amount</th>
        <td class="amount-total">${{ number_format($booking->total_amount, 2) }}</td>
      </tr>
      <tr>
        <th>Booking Status</th>
        <td><span class="status-badge">{{ ucfirst($booking->status) }}</span></td>
      </tr>
      <tr>
        <th>Payment Status</th>
        <td>
          <span class="status-badge" style="background-color: {{ $booking->isPaid() ? '#017b45' : '#1e293b' }}; color: {{ $booking->isPaid() ? '#ffffff' : '#66cbe6' }};">
            {{ strtoupper($booking->payment_status ?? 'unpaid') }}
          </span>
        </td>
      </tr>
      @if($booking->stripe_payment_intent_id)
      <tr>
        <th>Stripe Payment ID</th>
        <td><code>{{ $booking->stripe_payment_intent_id }}</code></td>
      </tr>
      @endif
      <tr>
        <th>Booking Date/Time</th>
        <td>{{ $booking->created_at ? $booking->created_at->format('F j, Y, g:i a') : now()->format('F j, Y, g:i a') }}</td>
      </tr>
    </table>
  </div>
  <div class="email-footer">
    &copy; {{ date('Y') }} RK Vibes. Automated system notification.
  </div>
</div>
</body>
</html>
