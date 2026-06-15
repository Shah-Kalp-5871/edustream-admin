<x-mail::message>
# Hello {{ $name }},

We noticed a new login to your EduStream account.

If this was you, you don't need to do anything. If not, please contact support or reset your password immediately.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
