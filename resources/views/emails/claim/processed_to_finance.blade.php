@component('mail::message')
# Expense Claim Processed

You have marked claim #{{ $claim->id }} as processed.
The mentor and their manager have been notified of the same.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
