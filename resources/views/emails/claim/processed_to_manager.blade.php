@component('mail::message')
# Expense Claim Processed

{{ $claim->mentor->name }} submitted an expense claim on {{ $claim->created_at->toFormattedDateString() }} for his session with {{ $claim->report->mentee->name }}.
You subsequently approved this claim and finance has now processed this claim.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
