@component('mail::message')
# Report Reminder

Hello,

We haven’t yet received a report from your planned session with {{ $mentee->name }} on {{ $plannedSession->date->toFormattedDateString() }}. <b>Please do this within the next working day.</b>

As part of your role, this needs to be submitted within 3 days of each meeting with your mentee. If your session hasn’t happened for any reason, please get in touch with the team to make them aware.

Please do get in touch if you need any further support in your role.

Thanks,<br>
{{ config('app.name') }}

@component('mail::button', ['url' => url('/calendar' )])
Calendar
@endcomponent

@component('mail::button', ['url' => url('/report/new' )])
Submit a Report
@endcomponent

@endcomponent
