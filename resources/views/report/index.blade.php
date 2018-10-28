@extends('spark::layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" id="report-list">
                    <div class="panel-heading">Submitted Session Reports</div>
                    <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th data-sortable="true">Report ID</th>
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
                                    <td class="session-date">{{ $report->session_date->toFormattedDateString() }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                    <div class="panel-body">
                        @if(Auth::user()->isDeveloper())
                            <a href="{{ url('report/export') }}">Download All Data as CSV</a>
                        @else
                            <a href="{{ url('/manager/report/export') }}">Download All Data as CSV</a>
                        @endif
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
