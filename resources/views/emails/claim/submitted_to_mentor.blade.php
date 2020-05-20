@inject('sessionReportService', 'App\Domains\SessionReports\Services\SessionReportService')

@component('mail::message')
@php
    $claimReport = $sessionReportService->getReport($claim->report_id)
@endphp
# Expense Claim Received

We have received your expense claim for your session with {{ $claimReport->mentee->name }} on {{ $claimReport->session_date->toFormattedDateString() }}.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
