@component('mail::message')
# Expense Claim Processed

The expense claim you submitted on {{ $claim->created_at->toFormattedDateString() }} for your session with {{ $sessionMentee }} on {{ $sessionDate }} has been processed.
You should expect the disbursement to arrive shortly.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
