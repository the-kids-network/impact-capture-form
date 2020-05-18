@inject('sessionReportService', 'App\Domains\SessionReports\Services\SessionReportService')

@component('mail::message')
@php
    $claimReport = $sessionReportService->getReport($claim->report_id)
@endphp
# Expense Claim Processed

The expense claim you submitted on {{ $claim->created_at->toFormattedDateString() }} for your session with {{ $claimReport->mentee->name }} has been processed.
You should expect the disbursement to arrive shortly.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
