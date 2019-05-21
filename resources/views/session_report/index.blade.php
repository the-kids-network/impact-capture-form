@extends('spark::layouts.app')

@section('content')
    <div class="container">
        @if(Request()->mentor_id)
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <a href="{{ url('report') }}">Clear mentor filter</a>
            </div>
        </div>
        <br/>
        @endif
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" id="report-list">
                    <div class="panel-heading">Submitted Session Reports</div>
                    <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th data-sortable="true">Session ID</th>
                                <th data-sortable="true">Mentor Name</th>
                                <th data-sortable="true">Mentee Name</th>
                                <th data-sortable="true">Session Length</th>
                                <th data-sortable="true">Session Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($reports as $report)
                                <tr class="clickable-row" data-href="{{ url('/report/'.$report->id) }}">
                                    <td class="report-id">{{ $report->id }}</td>
                                    <td class="mentor-name">{{ $report->mentor->name }}</td>
                                    <td class="mentee-name">{{ $report->mentee->name }}</td>
                                    <td class="session-length">{{ $report->length_of_session }}</td>
                                    <td class="session-date"><span class="hidden">{{ $report->session_date->timestamp }}</span>
                                        {{ $report->session_date->toFormattedDateString() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                    <div class="panel-body">
                        <a href="{{ route('report.export', ['mentor_id'=>Request()->mentor_id]) }}">Download All Data as CSV</a>
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

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
@endsection

@section('body-scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            $(".table").on("click", ".clickable-row", function() {
                window.location = $(this).data("href");
            });
        });
    </script>
@endsection
