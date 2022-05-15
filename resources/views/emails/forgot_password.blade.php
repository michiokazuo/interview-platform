@component('mail::message')
    Hi  <b>{{ $name }}</b>,

    {{ $body }}
    <b>{{ $note ?? '' }}</b>

    @component('mail::button', ['url' => $actionUrl ])
        {{ $actionText }}
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent
