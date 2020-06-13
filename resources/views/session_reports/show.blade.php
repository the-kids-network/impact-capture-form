@extends('layout.app')

@section('content')
    <div class="container session-report show">
        <div class="row">
            <div class="col-md-12">
                <nav class="nav page-nav">
                    <a class="nav-link" href="/report">Back to reports</a>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Session by {{ $report->mentor->name }} with {{ $report->mentee->name }} on {{ $report->session_date->toFormattedDateString() }}</div>
                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                                <tr class="session-id">
                                    <td class="label">Session ID</td>
                                    <td class="value">{{ $report->id }}</td>
                                </tr>
                                <tr class="mentor-name">
                                    <td class="label">Mentor Name</td>
                                    <td class="value">{{ $report->mentor->name }}</td>
                                </tr>
                                <tr class="mentee-name">
                                    <td class="label">Mentee Name</td>
                                    <td class="value">{{ $report->mentee->name }}</td>
                                </tr>
                                <tr class="session-date">
                                    <td class="label">Session Date</td>
                                    <td class="value">{{ $report->session_date->toFormattedDateString() }}</td>
                                </tr>
                                <tr class="session-rating">
                                    <td class="label">Session Rating</td>
                                    <td class="value">{{ $report->session_rating->value }}</td>
                                </tr>
                                <tr class="session-length">
                                    <td class="label">Session Length (Hours)</td>
                                    <td class="value">{{ $report->length_of_session }}</td>
                                </tr>
                                <tr class="activity-type">
                                    <td class="label">Activity Type</td>
                                    <td class="value">{{ $report->activity_type->name }}</td>
                                </tr>
                                <tr class="session-location">
                                    <td class="label">Location</td>
                                    <td class="value">{{ $report->location }}</td>
                                </tr>
                                <tr class="safeguarding-concern">
                                    <td class="label">Safeguarding Concern</td>
                                    <td class="value">
                                        @if($report->safeguarding_concern) 
                                            Yes - {{$report->safeguardingConcernTypeAttribute()}}
                                        @else 
                                            No
                                        @endif
                                    </td>
                                </tr>
                                <tr class="mentee-emotional-state">
                                    <td class="label">Mentee's Emotional State</td>
                                    <td class="value">{{ $report->emotional_state->name }}</td>
                                </tr>
                                <tr class="meeting-details">
                                    <td class="label">Meeting Details</td>
                                    <td class="value">{{ $report->meeting_details }}</td>
                                </tr>
                            </table> 
                        </div>
                   
                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                        <div class="modify-session-report">
                            <a class="submit btn btn-primary edit-report" 
                                href="/report/{{ $report->id }}/edit"><span class="fas fa-edit"></span> Edit</a>

                            <form class="delete-report" role="form" method="POST" action="/report/{{$report->id}}">
                                {{ csrf_field() }}
                                {{ method_field('delete') }}
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete-confirmation">
                                    <span class="fas fa-times"></span>
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
                                                    <span class="fas fa-times"></span>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card" id="expense-claim-list">
                    <div class="card-header">Expense Claims For Session</div>
                    <div class="card-body">
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