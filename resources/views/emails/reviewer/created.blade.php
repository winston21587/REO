<x-mail::message>
# Welcome to REO, {{ $user->first_name }}!

You have been assigned as a Reviewer for the REO System. 

Below are your login credentials for the Reviewer portal.

**Email:** {{ $user->email }}
<br>
**Password:** {{ $password }}

<x-mail::button :url="'https://reoph.site'">
Login to REO
</x-mail::button>

Best regards,<br>
REO System Management
</x-mail::message>
