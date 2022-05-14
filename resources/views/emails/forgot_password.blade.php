@component('mail::message')
    Hi  <b>{{ $name }}</b>,
    <br/>

    {{ $body }}
    <strong> {{ $note ?? '' }} </strong>

    @component('mail::button', ['url' => $actionUrl ])
        {{ $actionText }}
    @endcomponent

    Thanks,
    <br/>
    {{ config('app.name') }}
@endcomponent
