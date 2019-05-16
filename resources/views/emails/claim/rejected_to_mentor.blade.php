@component('mail::message')
# Expense Claim Rejected

The expense claim you submitted on {{ $claim->created_at->toFormattedDateString() }} has been rejected. Someone will be in touch shortly.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
