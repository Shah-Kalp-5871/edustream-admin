<x-mail::message>
# Account Deletion Verification

Hello,

You have requested to delete your account at **Gujju Scholar**. To proceed with this request, please use the following One-Time Password (OTP):

<x-mail::panel>
# {{ $otp }}
</x-mail::panel>

This OTP is valid for the next 10 minutes. If you did not make this request, please ignore this email.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
