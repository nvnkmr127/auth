<x-mail::message>
    # Secure Login Verification

    You are receiving this email because a login attempt was made for your account on the **Auth Portal**.

    Your verification code is:

    <x-mail::panel>
        # {{ $code }}
    </x-mail::panel>

    This code will expire in **10 minutes**. If you did not attempt to log in, please ignore this email or contact
    support if you suspect unauthorized access.

    Thanks,<br>
    {{ config('app.name') }} Security Team
</x-mail::message>