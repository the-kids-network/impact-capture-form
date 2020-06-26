@component('mail::message')
# Expense Claim Received

We have received your expense claim for your session with {{ $session['mentee_name'] }} on {{ $session['session_date']->toFormattedDateString() }}.

@component('mail::button', ['url' =>  url('/app#/expenses/' . $claim->id)  ] )
View Expense Claim
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
