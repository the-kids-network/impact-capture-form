@component('mail::message')
# Expense Claim Approved

Your manager approved the expense claim you submitted on {{ $claim->created_at->toFormattedDateString() }} for your meeting with {{ $claim->report->mentee->first_name }} on {{ $claim->report->session_date->toFormattedDateString() }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
