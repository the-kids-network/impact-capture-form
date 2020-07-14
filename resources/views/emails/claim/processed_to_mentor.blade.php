@component('mail::message')
# Expense Claim Processed

The expense claim you submitted on {{ $claim->created_at->toFormattedDateString() }} for your session with {{ $session['mentee_name'] }} on {{ $session['session_date']->toFormattedDateString() }} has been processed.
You should expect the disbursement to arrive shortly.

@component('mail::button', ['url' =>  url('/app#/expenses/' . $claim->id)  ] )
View Expense Claim
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
