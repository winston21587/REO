<x-mail::message>
# Welcome to REO, {{ $user->first_name }}!

You have been assigned as an Administrator for the REO System. 

Below is your temporary 6-digit OTP to access the Administrator portal. You will be required to change your password upon your first login.

**Email:** {{ $user->email }}
<br>
**Temporary OTP:** {{ $password }}

<x-mail::button :url="'https://reoph.site'">
Login to REO
</x-mail::button>

Best regards,<br>
REO System Management
</x-mail::message>
