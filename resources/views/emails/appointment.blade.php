<h1>Hello {{ $user->name }},</h1>

<p>Your research titled <strong>{{ $research->Study_Protocol_title }}</strong> has been marked as <strong>For Initial Review</strong>.</p>

<p><strong>Appointment Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F j, Y, g:i A') }}</p>

<p>Please be ready on that date for your initial review session.</p>

<p>Thank you,<br>
The REO Admin Team</p>
