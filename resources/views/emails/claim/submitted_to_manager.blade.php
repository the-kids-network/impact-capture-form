@component('mail::message')
# Expense Claim From {{ $claim->mentor->name }}

{{ $claim->mentor->name }} has submitted an expense claim for their session with {{ $claim->report->mentee->first_name }} on {{ $claim->report->session_date->toFormattedDateString() }}.
There is no need to approve the claim anymore, an administrator will process it from here.

@component('mail::button', ['url' => url('/expense-claim/' . $claim->id)])
View Expense Claim
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
