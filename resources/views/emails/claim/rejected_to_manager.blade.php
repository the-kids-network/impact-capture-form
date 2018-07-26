@component('mail::message')

# Expense Claim Rejected

You rejected the expense claim submitted on {{ $claim->created_at->toFormattedDateString() }} by {{  $claim->mentor->name }}. Please reach out to {{ $claim->mentor->name }} to ensure they understand why their claim was rejected.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
