@component('mail::message')
# Report Reminder

Hello,

We haven’t yet received a report from your session with {{ $mentee->getNameAttribute() }} on {{ $schedule->next_session_date->toFormattedDateString() }}. <b>Please do this within the next working day.</b>

As part of your role, this needs to be submitted within 3 days of each meeting with your mentee. If your session hasn’t happened for any reason, please get in touch with the team to make them aware.

Please do get in touch if you need any further support in your role.

Thanks,<br>
The Kids Network

@component('mail::button', ['url' => url('/calendar' )])
Scheduled Sessions Calendar
@endcomponent

@component('mail::button', ['url' => url('/my-reports' )])
Submit a Report
@endcomponent

@endcomponent
