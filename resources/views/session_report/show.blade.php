@extends('layout.app')

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
                            <td>Session ID</td>
                            <td>{{ $report->id }}</td>
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
                            <td>Session Length (Hours)</td>
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
                            <td>
                                @if($report->safeguarding_concern) 
                                    Yes - {{$report->safeguardingConcernTypeAttribute()}}
                                @else 
                                    No
                                @endif
                            </td>
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
                    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                    <div class="session-report-modify-container">
                        <a class="submit btn btn-primary edit-report" 
                            href="/report/{{ $report->id }}/edit"><span class="glyphicon glyphicon-pencil"></span> Edit</a>

                        <form class="form-horizontal delete-report" role="form" method="POST" action="/report/{{$report->id}}">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-confirmation">
                                <span class="glyphicon glyphicon-remove"></span>
                                <span>Delete</span>
                            </button>

                            <div class="modal fade" id="delete-confirmation" tabindex="-1" role="dialog" aria-labelledby="delete report" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="exampleModalLabel">Confirm deletion of report</h3>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the session report?</p>

                                            <p>This will <b>delete all associated expense claims</b>, so make sure they have not been processed / paid already.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">
                                                <span class="glyphicon glyphicon-remove"></span>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->isManager() || Auth::user()->isAdmin())
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" id="expense-claim-list">
                    <div class="panel-heading">Expense Claims For Session</div>
                    <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th data-sortable="true">Claim ID</th>
                                <th data-sortable="true">Created On</th>
                                <th data-sortable="true">Status</th>
                                <th data-sortable="true">Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($claims as $claim)
                                <tr class="clickable-row" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                    <td>{{ $claim->id }}</td>
                                    <td>{{ $claim->created_at->toFormattedDateString() }}</td>
                                    <td class="text-capitalize">{{ $claim->status }}</td>
                                    <td>{{ $claim->expenses->sum('amount') }}
                                </td>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
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