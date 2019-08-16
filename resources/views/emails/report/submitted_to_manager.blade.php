@component('mail::message')
# Report Received

{{ $report->mentor->name }} has submitted a session report for his meeting with {{ $report->mentee->name }} on {{ $report->session_date->toFormattedDateString() }}

@component('mail::button', ['url' =>  url('/report/' . $report->id)  ] )
View Report
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
