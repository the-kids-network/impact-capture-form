@component('mail::message')
# Expense Claim From {{ $claim->mentor->name }}

{{ $claim->mentor->name }} has submitted an expense claim for his session with {{ $claim->report->mentee->first_name }} on {{ $claim->report->session_date->toFormattedDateString() }}.

@component('mail::button', ['url' => url('/expense-claim/' . $claim->id)])
View Expense Claim
@endcomponent

Thanks,<br>
The Kids Network
@endcomponent
