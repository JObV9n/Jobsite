    <x-mail::message>
# Introduction

The body of your message.
        @break
Here is your 6 digit pincode.
        @break
<x-mail::button :url="''">
{{$pincode}}
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
