@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" id="expense-claim-list">
                    <div class="panel-heading">Awaiting Processing</div>
                    <div class="panel-body">
                        <p>This table only lists claims that are in finance's queue for processing</p>
                    </div>
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
                                <tr class="clickable-row" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                    <td>{{ $claim->id }}</td>
                                    <td>{{ $claim->mentor->name }}</td>
                                    <td>With {{ $claim->report->mentee->first_name . ' ' . $claim->report->mentee->last_name }} on {{ $claim->report->session_date->toFormattedDateString() }}</td>
                                    <td>{{ $claim->created_at->toFormattedDateString() }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" id="processed-expense-claim-list">
                    <div class="panel-heading">Previously Processed</div>
                    <div class="panel-body">
                        <p>This table only lists claims that you have processed in the past</p>
                    </div>
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
                            @if($claim->status == 'processed')
                            <tr class="clickable-row" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                <td>{{ $claim->id }}</td>
                                <td>{{ $claim->mentor->name }}</td>
                                <td>With {{ $claim->report->mentee->first_name . ' ' . $claim->report->mentee->last_name }} on {{ $claim->report->session_date->toFormattedDateString() }}</td>
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

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" id="processed-expense-claim-list">
                    <div class="panel-heading">Previously Rejected</div>
                    <div class="panel-body">
                        <p>This table only lists claims that you have rejected in the past</p>
                    </div>
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
                            @if($claim->status == 'rejected')
                            <tr class="clickable-row" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                <td>{{ $claim->id }}</td>
                                <td>{{ $claim->mentor->name }}</td>
                                <td>With {{ $claim->report->mentee->first_name . ' ' . $claim->report->mentee->last_name }} on {{ $claim->report->session_date->toFormattedDateString() }}</td>
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
