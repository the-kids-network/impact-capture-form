@component('mail::message')

# Expense Claim Rejected

The expense claim submitted on {{ $claim->created_at->toFormattedDateString() }} by {{  $claim->mentor->name }} was rejected.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
