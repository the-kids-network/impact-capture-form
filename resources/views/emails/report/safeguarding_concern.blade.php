@component('mail::message')
# Safeguarding Concern

{{ $report->mentor->name }} has submitted a session report with a safeguarding concern.

It has been marked as: {{ strtoupper($report->safeguardingConcernTypeAttribute()) }}.

Please view the session report for more details and then get in contact with the mentor as soon as possible to discuss.

@component('mail::button', ['url' =>  url('/app#/session-reports/' . $report->id)  ] )
View Report
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
