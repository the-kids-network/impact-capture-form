@component('mail::message')
# Report Reminder

Hello,

We haven’t yet received a report from your planned session with {{ $mentee->name }} on {{ $plannedSession->date->toFormattedDateString() }}. <b>Please do this within the next working day.</b>

As part of your role, this needs to be submitted within 3 days of each meeting with your mentee. 

If the planned session changed or did not happen, you can change / remove the planned session via the calendar.

Please do get in touch if you need any further support in your role.

Thanks,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => url('/planned-session/'.$plannedSession->id )])
Change / Delete Planned Session
@endcomponent

@component('mail::button', ['url' => url('/calendar' )])
Calendar
@endcomponent

@component('mail::button', ['url' => url('/report/new' )])
Submit a Report
@endcomponent

@endcomponent
