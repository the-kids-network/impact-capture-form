@inject('sessionReportService', 'App\Domains\SessionReports\Services\SessionReportService')

@extends('layout.app')

@section('content')
    <div class="container expense-claim list">
        @if(Request()->mentor_id)
        <div class="row">
            <div class="col-md-12">
                <a href="{{ url('expense-claim') }}">Clear mentor filter</a>
            </div>
        </div>
        <br/>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="expense-claim items">
                    <div class="card-header">Submitted Expense Claims</div>
                    <div class="card-body">
                        <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Claim ID</th>
                                    <th data-sortable="true">Mentor Name</th>
                                    <th data-sortable="true">Session</th>
                                    <th data-sortable="true">Created On</th>
                                    <th data-sortable="true">Status</th>
                                    <th data-sortable="true">Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($expense_claims as $claim)
                                    @php
                                        $claimReport = $sessionReportService->getReport($claim->report_id)
                                    @endphp
                                    <tr class="clickable-row expense-claim item" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                        <td class="claim-id">{{ $claim->id }}</td>
                                        <td class="mentor-name">{{ $claim->mentor->name }}</td>
                                        <td class="session">With {{ $claimReport->mentee->name }} on {{ $claimReport->session_date->toFormattedDateString() }}</td>
                                        <td class="created-date">{{ $claim->created_at->toFormattedDateString() }}</td>
                                        <td class="text-capitalize status">{{ $claim->status }}</td>
                                        <td class="amount">{{ $claim->expenses->sum('amount') }}</td>
                                    </td>
                                @endforeach
                            </tbody>
                        </table>

                        <a href="{{ route('expense-claim.export', ['mentor_id'=>Request()->mentor_id]) }}">Download All Data as CSV</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .clickable-row{
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