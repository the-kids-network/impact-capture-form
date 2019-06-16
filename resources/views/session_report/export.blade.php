@extends('layout.app')

@section('content')

    <div class="container">

        <div class="row m-b-lg">
            <div class="col-md-12">
                <button class="btn btn-lg btn-primary btn-block" onclick="exportTableToCSV('data.csv')">Click to Download Data as CSV</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table id="test-table" class="table table-bordered table-responsive table-striped">
                    <tr>
                        <th>Session ID</th>
                        <th>Mentor</th>
                        <th>Mentee</th>
                        <th>Session Date</th>
                        <th>Length of Session</th>
                        <th>Activity Type</th>
                        <th>Location</th>
                        <th>Safeguarding Concern</th>
                        <th>Physical Appearance</th>
                        <th>Emotional State</th>
                        <th>Meeting Details</th>
                    </tr>

                    @foreach($reports as $report)
                        <tr>
                            <td>{{ $report->id }}</td>
                            <td>{{ $report->mentor->name }}</td>
                            <td>{{ $report->mentee->first_name }} {{ $report->mentee->last_name }}</td>
                            <td>{{ $report->session_date->format('d-m-y') }}</td>
                            <td>{{ $report->length_of_session }}</td>
                            <td>{{ $report->activity_type->name }}</td>
                            <td>{{ $report->location }}</td>
                            <td>@if($report->safeguarding_concern) Yes @else No @endif</td>
                            <td>{{ $report->physical_appearance->name }}</td>
                            <td>{{ $report->emotional_state->name }}</td>
                            <td>{{ $report->meeting_details }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

    </div>
@endsection

@section('body-scripts')
    <script src="/js/jquery.TableCSVExport.js"></script>

    <script>
        function exportTableToCSV(filename){
            $(document).ready(function() {
                $('#test-table').TableCSVExport({
                    delivery: 'download',
                    filename: filename
                });
            });
        }
    </script>

@endsection