@component('mail::message')
# Expense Claim Processed

You have marked claim #{{ $claim->id }} as processed.
The approving manager and the mentor have been notified of the same.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
