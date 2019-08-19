@component('mail::message')
# Mentor Changed Planned Session

@if($typeOfChange == 'delete')
{{ $mentor->name }} deleted planned session previously scheduled for {{ $plannedSession->date->toFormattedDateString() }}.
@elseif($typeOfChange == 'change')
{{ $mentor->name }} changed planned session.
@endif

Thanks,<br>
{{ config('app.name') }}

@if($typeOfChange == 'change')
@component('mail::button', ['url' => url('/planned-session/'.$plannedSession->id )])
Changed Session
@endcomponent
@endif

@component('mail::button', ['url' => url('/calendar' )])
Calendar
@endcomponent

@endcomponent
