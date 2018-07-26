@component('mail::message')
# Report Received

{{ $report->mentor->name }} has submitted a session report for his meeting with {{ $report->mentee->first_name }} on {{ $report->session_date->toFormattedDateString() }}

@component('mail::button', ['url' =>  url('/report/' . $report->id)  ] )
View Report
@endcomponent

Thanks,<br>
The Kids Network
@endcomponent
