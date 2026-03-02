<x-mail::message>
# Welcome to REO, {{ $user->first_name }}!

You have been assigned as a Reviewer for the REO System. 

Below are your account details.

**Email:** {{ $user->email }}

*Note: Reviewer accounts do not have direct login access to the REO system.*

Best regards,<br>
REO System Management
</x-mail::message>
