@component('mail::message')
# Expense Claim Received

We have received your expense claim for your session with {{ $claim->report->mentee->first_name }} on {{ $claim->report->session_date->toFormattedDateString() }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
