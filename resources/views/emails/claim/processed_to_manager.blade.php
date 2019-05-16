@component('mail::message')
# Expense Claim Processed

{{ $claim->mentor->name }} submitted an expense claim on {{ $claim->created_at->toFormattedDateString() }} for their session with {{ $claim->report->mentee->name }}.
This claim has now been processed by an administrator.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
