@component('mail::message')
# Report Received

We have received your report for {{ $report->mentee->name }} on {{ $report->session_date->toFormattedDateString() }}

@component('mail::button', ['url' =>  url('/app#/session-reports/' . $report->id)  ] )
View Report
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
