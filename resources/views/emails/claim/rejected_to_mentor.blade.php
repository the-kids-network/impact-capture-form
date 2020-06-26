@component('mail::message')
# Expense Claim Rejected

The expense claim you submitted on {{ $claim->created_at->toFormattedDateString() }} has been rejected. Someone will be in touch shortly.

@component('mail::button', ['url' =>  url('/app#/expenses/' . $claim->id)  ] )
View Expense Claim
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
