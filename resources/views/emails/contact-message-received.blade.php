<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Contact Message</title>
<style>
  body { margin: 0; padding: 0; background-color: #0b0f14; font-family: 'Helvetica Neue', Arial, sans-serif; color: #f2f2f0; line-height: 1.6; }
  .email-wrap { max-width: 600px; margin: 30px auto; background-color: #141c28; border: 1px solid #223043; border-radius: 6px; overflow: hidden; }
  .email-header { background: linear-gradient(135deg, #017b45 0%, #375fac 100%); padding: 25px 30px; border-bottom: 2px solid #66cbe6; }
  .email-header h1 { margin: 0; font-size: 20px; color: #ffffff; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; }
  .email-body { padding: 30px; }
  .email-body h2 { margin-top: 0; font-size: 18px; color: #ffffff; border-bottom: 1px solid #223043; padding-bottom: 12px; }
  .info-table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; }
  .info-table th { text-align: left; padding: 10px 0; font-size: 13px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; width: 35%; border-bottom: 1px solid #1f2b3c; }
  .info-table td { padding: 10px 0; font-size: 14px; color: #f2f2f0; border-bottom: 1px solid #1f2b3c; }
  .message-box { background-color: #0f1621; border-left: 3px solid #017b45; padding: 18px 20px; margin-top: 15px; font-size: 14.5px; color: #e5e5e5; white-space: pre-wrap; border-radius: 0 4px 4px 0; }
  .email-footer { background-color: #0f1621; padding: 20px 30px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #223043; }
</style>
</head>
<body>
<div class="email-wrap">
  <div class="email-header">
    <h1>RK Vibes — New Contact Inquiry</h1>
  </div>
  <div class="email-body">
    <h2>Client Message Details</h2>
    <p>A new inquiry was submitted via the contact form on the RK Vibes website:</p>
    
    <table class="info-table">
      <tr>
        <th>Full Name</th>
        <td><strong>{{ $contactMessage->name }}</strong></td>
      </tr>
      <tr>
        <th>Email Address</th>
        <td><a href="mailto:{{ $contactMessage->email }}" style="color: #66cbe6; text-decoration: none;">{{ $contactMessage->email }}</a></td>
      </tr>
      <tr>
        <th>Phone Number</th>
        <td>{{ $contactMessage->phone ?? 'Not provided' }}</td>
      </tr>
      <tr>
        <th>Project Interest</th>
        <td><strong style="color: #66cbe6;">{{ $contactMessage->subject ?? 'General Inquiry' }}</strong></td>
      </tr>
      <tr>
        <th>Submission Date</th>
        <td>{{ $contactMessage->created_at ? $contactMessage->created_at->format('F j, Y, g:i a') : now()->format('F j, Y, g:i a') }}</td>
      </tr>
    </table>

    <label style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; font-weight: bold;">Project Message:</label>
    <div class="message-box">
{{ $contactMessage->message }}
    </div>
  </div>
  <div class="email-footer">
    &copy; {{ date('Y') }} RK Vibes. Automated system notification.
  </div>
</div>
</body>
</html>
