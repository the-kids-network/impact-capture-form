@component('mail::message')
# Expense Claim Approved

You approved an expense claim by {{ $claim->mentor->name }}. {{ $claim->mentor->name }} had submitted this claim on {{ $claim->created_at->toFormattedDateString() }} for their meeting with {{ $claim->report->mentee->first_name }} on {{ $claim->report->session_date->toFormattedDateString() }}.

@component('mail::button', ['url' => url('/expense-claim/'.$claim->id)])
View Approved Claim
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
