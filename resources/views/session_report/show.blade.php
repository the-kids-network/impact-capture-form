@extends('spark::layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Session by {{ $report->mentor->name }} with {{ $report->mentee->name }} on {{ $report->session_date->toFormattedDateString() }}</div>
                    <table class="table">
                        <tr>
                            <th class="col-xs-4">Field</th>
                            <th class="col-xs-8">Value</th>
                        </tr>

                        <tr>
                            <td>Mentor Name</td>
                            <td>{{ $report->mentor->name }}</td>
                        </tr>

                        <tr>
                            <td>Mentee Name</td>
                            <td>{{ $report->mentee->name }}</td>
                        </tr>

                        <tr>
                            <td>Session Date</td>
                            <td>{{ $report->session_date->toFormattedDateString() }}</td>
                        </tr>

                        <tr>
                            <td>Session Rating</td>
                            <td>{{ $report->session_rating->value }}</td>
                        </tr>

                        <tr>
                            <td>Session Length</td>
                            <td>{{ $report->length_of_session }}</td>
                        </tr>

                        <tr>
                            <td>Activity Type</td>
                            <td>{{ $report->activity_type->name }}</td>
                        </tr>

                        <tr>
                            <td>Location</td>
                            <td>{{ $report->location }}</td>
                        </tr>

                        <tr>
                            <td>Safeguarding Concern</td>
                            <td>@if($report->safeguarding_concern) Yes @else No @endif</td>
                        </tr>

                        <tr>
                            <td>Mentee's Physical Appearance</td>
                            <td>{{ $report->physical_appearance->name }}</td>
                        </tr>

                        <tr>
                            <td>Mentee's Emotional State</td>
                            <td>{{ $report->emotional_state->name }}</td>
                        </tr>

                        <tr>
                            <td>Meeting Details</td>
                            <td>{{ $report->meeting_details }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection