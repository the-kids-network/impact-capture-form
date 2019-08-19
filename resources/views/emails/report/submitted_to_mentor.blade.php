@component('mail::message')
# Report Received

We have received your report for {{ $report->mentee->name }} on {{ $report->session_date->toFormattedDateString() }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
