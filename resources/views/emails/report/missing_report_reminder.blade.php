@component('mail::message')
# Report Reminder

Hi,

Just a quick reminder to submit this week's report for {{ $mentee->getNameAttribute() }}.

@component('mail::button', ['url' => url('/my-reports' )])
Submit Report
@endcomponent

Thanks,<br>
The Kids Network
@endcomponent
