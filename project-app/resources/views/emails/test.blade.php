<x-mail::message>
# Test Email

This is a test email from your Laravel app using Gmail.


<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
