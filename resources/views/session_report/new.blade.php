@extends('layout.app')

@section('content')
    <div class="container session-report new">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Weekly Session Report</div>

                    <div class="card-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/report">
                            {{ csrf_field() }}
                            <input type="hidden" name="mentor_id" value="{{ Auth::user()->id }}"/>

                            <!-- Mentee's Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="menteeSelect">Mentee</label>
                                <div class="col-md-6">
                                    <select id="menteeSelect" class="form-control" name="mentee_id">
                                        @foreach($mentees as $mentee)
                                            <option value="{{ $mentee->id }}" @if( old('mentee_id') == $mentee->id) selected="selected" @endif>
                                                {{ $mentee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Date of Session -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="sessionDateInput">Session Date</label>
                                <div class="col-md-6">
                                    <input id="sessionDateInput" type="text" class="form-control datepicker" name="session_date" 
                                           value="{{ old('session_date') }}" autocomplete="off">
                                </div>
                            </div>
                            <!-- Session Rating -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="ratingSelect"
                                       data-toggle="popover" data-trigger="hover" data-content="{!! 'These could relate to the chosen activity, your mentee’s emotional state during the session or the outcomes of the session.' !!}">Please rate your session <i class="fas fa-info-circle"></i></label>
                                <div class="col-md-6">
                                    <select id="ratingSelect" class="form-control" name="rating_id">
                                        @foreach($session_ratings as $rating)
                                            <option value="{{ $rating->id }}" @if( old('rating_id') == $rating->id) selected="selected" @endif>
                                                    {{ $rating->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Length of Session -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="lengthInput">Length of Session (hours)</label>
                                <div class="col-md-6">
                                    <input id="lengthInput" type="text" class="form-control" name="length_of_session" value="{{ old('length_of_session') }}">
                                </div>
                            </div>
                            <!-- Type of Activity -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="activityTypeSelect">Activity Type</label>
                                <div class="col-md-6">
                                    <select id="activityTypeSelect" class="form-control" name="activity_type_id">
                                        @foreach($activity_types as $activity_type)
                                            <option value="{{ $activity_type->id }}" @if( old('activity_type_id') == $activity_type->id) selected="selected" @endif>
                                                {{ $activity_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Location -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="locationInput">Location</label>
                                <div class="col-md-6">
                                    <input id="locationInput" type="text" class="form-control" name="location" value="{{ old('location') }}">
                                </div>
                            </div>
                            <!-- Safeguarding Concern -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="safeguardingSelect">Safeguarding Concern</label>
                                <div class="col-md-6">
                                    <select id="safeguardingSelect" class="form-control" name="safeguarding_concern">
                                        <option value="0" @if( old('safeguarding_concern') == 0) selected="selected" @endif>No</option>
                                        <option value="1" @if( old('safeguarding_concern') == 1) selected="selected" @endif>Yes - Serious concern (please complete safeguarding cause for concern form)</option>
                                        <option value="2" @if( old('safeguarding_concern') == 2) selected="selected" @endif>Yes - Mild concern (please outline in report)</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Emotional State -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="emotionalStateSelect">Mentee's Emotional State</label>
                                <div class="col-md-6">
                                    <select id="emotionalStateSelect" class="form-control" name="emotional_state_id">
                                        @foreach($emotional_states as $emotional_state)
                                            <option value="{{ $emotional_state->id }}" @if( old('emotional_state_id') == $emotional_state->id ) selected="selected" @endif>
                                                {{ $emotional_state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Meeting Details -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="meetingDetailsInput">Meeting Details</label>
                                <div class="col-md-6">
                                    <textarea id="meetingDetailsInput" class="form-control" rows="10" name="meeting_details">{{ old('meeting_details') }}</textarea>
                                </div>
                            </div>

                            <hr class="card-divider"/>

                            <!-- Next Planned Session Date -->
                            <h5 class="card-title">Next Session</h5>
                            <div class="alert alert-info" role="alert">
                                You can amend this later via the <a href="/calendar">Calendar</a>.
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="nextSessionDateInput"
                                       data-toggle="popover" data-trigger="hover"
                                       data-content="Email reminders will be sent if a report has not been saved within three days of a planned session.">
                                    Date <i class="fas fa-info-circle"></i>
                                </label>

                                <div class="col-md-6">
                                    <input id="nextSessionDateInput" type="text" class="form-control datepicker" name="next_session_date" value="{{ old('next_session_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Next Planned Session Location -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="nextSessionLocationInput">Location</label>
                                <div class="col-md-6">
                                    <input id="nextSessionLocationInput" type="text" class="form-control" name="next_session_location" value="{{ old('next_session_location') }}">
                                </div>
                            </div>

                            <hr class="card-divider"/>

                            <!-- Leave -->
                            <h5 class="card-title">Leave (Holiday)</h5>

                            <div class="alert alert-info" role="alert">
                                Fill in this section if you have some leave / time away to record for yourself or your mentee.<br/>
                                You can amend this later via the <a href="/calendar">Calendar</a>.
                            </div>

                            @include('mentor_leave.info_message')

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Leave Type</label>
                                <div class="col-md-6">
                                    <div class="form-check form-check-inline">
                                        <input id="leaveTypeMentorInput" class="form-check-input leave-type-mentor " type="radio" name="leave_type" value="mentor" 
                                                @if( old('leave_type') == 'mentor' ) checked @elseif( old('leave_type') == null ) checked @endif>
                                        <label class="form-check-label" for="leaveTypeMentorInput">Mentor</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input id="leaveTypeMenteeInput" class="form-check-input leave-type-mentee" type="radio" name="leave_type" value="mentee"
                                                @if( old('leave_type') == 'mentee' ) checked @endif>
                                        <label class="form-check-label" for="leaveTypeMenteeInput">Mentee</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="leaveStartDateInput"
                                       data-toggle="popover" data-trigger="hover" data-content="The first day of the leave.">
                                    Start Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input id="leaveStartDateInput" type="text" class="form-control datepicker" name="leave_start_date" value="{{ old('leave_start_date') }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="leaveEndDateInput"
                                       data-toggle="popover" data-trigger="hover" data-content="The last day (inclusive) of the leave.">
                                    End Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input id="leaveEndDateInput" type="text" class="form-control datepicker" name="leave_end_date" value="{{ old('leave_end_date') }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="leaveDescriptionInput">Description</label>
                                <div class="col-md-6">
                                    <input id="leaveDescriptionInput" type="text" class="form-control" name="leave_description" value="{{ old('leave_description') }}">
                                </div>
                            </div>

                            <hr/>

                            <!-- Submit Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" name="report_submit" class="btn btn-primary">
                                        <span class="fas fa-paper-plane"></span> Submit
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Submitted Session Reports</div>
                    <ul class="list-group">
                        @foreach($reports as $report)
                            <li class="list-group-item">
                                {{ $report->mentee->name }}
                                <div class="float-right">
                                    {{ $report->session_date->toFormattedDateString()  }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body-scripts')
    <script>
        $(document).ready(function() {
            $(function() {
                $( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
            });

            $('[data-toggle="popover"]').popover({
                html: true,
                placement: 'left'
            });
          });
    </script>

@endsection