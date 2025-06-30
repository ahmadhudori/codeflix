<x-mail::message>
# Introduction

The body of your message.

your expired date : {{ $expired_date }}

<x-mail::button :url="$renew_url">
Renew Membership
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
