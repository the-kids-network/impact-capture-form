@extends('layout.app')

@section('content')
    <div class="container session-report list">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        @if(Request()->mentor_id)
                        Submitted Session Reports For Mentor: {{ Request()->mentor_id }}
                        @else
                        Submitted Session Reports
                        @endif
                    </div>
                    <div class="card-body">
                        @if(Request()->mentor_id)
                        <a href="{{ url('report') }}">Clear mentor filter</a>
                        <br/>
                        @endif
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
                                        <td class="session-date"><span class="d-none">{{ $report->session_date->timestamp }}</span>
                                            {{ $report->session_date->toFormattedDateString() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

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
