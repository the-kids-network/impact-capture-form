@component('mail::message')
# Expense Claim Received

We have received your expense claim for your session with {{ $sessionMentee }} on {{ $sessionDate }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
