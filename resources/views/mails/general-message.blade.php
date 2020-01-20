@component('mail::message')

{{ $message->content }}

Thanks,<br>
{{ $user->name }}
@endcomponent
