@inject('sessionReportService', 'App\Domains\SessionReports\Services\SessionReportService')

@extends('layout.app')

@section('content')
    <div class="container expense-claim processing">
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="expense-claim-list">
                    <div class="card-header">Awaiting Processing</div>
                    <div class="card-body">
                        <p>This table only lists claims that are in finance's queue for processing</p>
                    
                        <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Claim ID</th>
                                    <th data-sortable="true">Mentor Name</th>
                                    <th data-sortable="true">Session</th>
                                    <th data-sortable="true">Created On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pending_claims as $claim)
                                    @php
                                        $claimReport = $sessionReportService->getReport($claim->report_id)
                                    @endphp
                                    <tr class="clickable-row" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                        <td>{{ $claim->id }}</td>
                                        <td>{{ $claim->mentor->name }}</td>
                                        <td>With {{ $claimReport->mentee->name }} on {{ $claimReport->session_date->toFormattedDateString() }}</td>
                                        <td>{{ $claim->created_at->toFormattedDateString() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="processed-expense-claim-list">
                    <div class="card-header">Previously Processed</div>
                    <div class="card-body">
                        <p>This table only lists claims that you have processed in the past</p>
                    
                        <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Claim ID</th>
                                    <th data-sortable="true">Mentor Name</th>
                                    <th data-sortable="true">Session</th>
                                    <th data-sortable="true">Created On</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($processed_claims as $claim)
                                @php
                                    $claimReport = $sessionReportService->getReport($claim->report_id)
                                @endphp
                                @if($claim->status == 'processed')
                                <tr class="clickable-row" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                    <td>{{ $claim->id }}</td>
                                    <td>{{ $claim->mentor->name }}</td>
                                    <td>With {{ $claimReport->mentee->name }} on {{ $claimReport->session_date->toFormattedDateString() }}</td>
                                    <td>{{ $claim->created_at->toFormattedDateString() }}</td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="processed-expense-claim-list">
                    <div class="card-header">Previously Rejected</div>
                    <div class="card-body">
                        <p>This table only lists claims that you have rejected in the past</p>
                        <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Claim ID</th>
                                    <th data-sortable="true">Mentor Name</th>
                                    <th data-sortable="true">Session</th>
                                    <th data-sortable="true">Created On</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($processed_claims as $claim)
                                @php
                                    $claimReport = $sessionReportService->getReport($claim->report_id)
                                @endphp
                                @if($claim->status == 'rejected')
                                <tr class="clickable-row" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                    <td>{{ $claim->id }}</td>
                                    <td>{{ $claim->mentor->name }}</td>
                                    <td>With {{ $claimReport->mentee->name }} on {{ $claimReport->session_date->toFormattedDateString() }}</td>
                                    <td>{{ $claim->created_at->toFormattedDateString() }}</td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .clickable-row {
            cursor: pointer;
        }
    </style>
@endsection

@section('body-scripts')
    <script>
        jQuery(document).ready(function($) {
            $(".table").on("click", ".clickable-row", function() {
                window.location = $(this).data("href");
            });
        });
    </script>
@endsection
