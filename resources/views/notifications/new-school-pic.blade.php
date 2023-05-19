<x-mail::message>
# Introduction

The body of your message.

Nama : {{ $user->name }}

Email : {{ $user->email }}

Password : {{ $password }}

Role : {{ str($user->getRoleNames()[0])->title }}

<x-mail::button :url="route('login')">
Masuk ke Dashboard Sekolah
</x-mail::button>

Segera ubah password di atas dengan yang lebih aman.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
