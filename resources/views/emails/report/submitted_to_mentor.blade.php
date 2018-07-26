@component('mail::message')
# Report Received

We have received your report for {{ $report->mentee->first_name }} on {{ $report->session_date->toFormattedDateString() }}

Thanks,<br>
The Kids Network
@endcomponent
