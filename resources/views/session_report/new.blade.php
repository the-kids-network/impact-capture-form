@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Weekly Session Report</div>

                    <div class="panel-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/report">
                        {{ csrf_field() }}

                            <input type="hidden" name="mentor_id" value="{{ Auth::user()->id }}"/>

                            <!-- Mentee's Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="mentee_id">
                                        @foreach($mentees as $mentee)
                                            <option value="{{ $mentee->id }}" @if( old('mentee_id') == $mentee->id) selected="selected" @endif>
                                                {{ $mentee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Date of Session -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Session Date</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="session_date" value="{{ old('session_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Session Rating -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover" data-content="{!! 'These could relate to the chosen activity, your mentee’s emotional state during the session or the outcomes of the session.' !!}">Please rate your session <i class="fas fa-info-circle"></i></label>
                                <div class="col-md-6">
                                    <select class="form-control" name="rating_id">
                                        @foreach($session_ratings as $rating)
                                            <option value="{{ $rating->id }}" @if( old('rating_id') == $rating->id) selected="selected" @endif>
                                                    {{ $rating->value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Length of Session -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Length of Session (hours)</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="length_of_session" value="{{ old('length_of_session') }}">
                                </div>
                            </div>

                            <!-- Type of Activity -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Activity Type</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="activity_type_id">
                                        @foreach($activity_types as $activity_type)
                                            <option value="{{ $activity_type->id }}" @if( old('activity_type_id') == $activity_type->id) selected="selected" @endif>
                                                {{ $activity_type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Location</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="location" value="{{ old('location') }}">
                                </div>
                            </div>

                            <!-- Safeguarding Concern -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Safeguarding Concern</label>

                                <div class="col-md-6">
                                    <select class="form-control" name="safeguarding_concern">
                                        <option value="0" @if( old('safeguarding_concern') == 0) selected="selected" @endif>No</option>
                                        <option value="1" @if( old('safeguarding_concern') == 1) selected="selected" @endif>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Mentee's Physical Appearance -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee's Physical Appearance</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="physical_appearance_id">
                                        @foreach($physical_appearances as $physical_appearance)
                                            <option value="{{ $physical_appearance->id }}" @if( old('physical_appearance_id') == $physical_appearance->id ) selected="selected" @endif>
                                                {{ $physical_appearance->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Emotional State -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee's Emotional State</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="emotional_state_id">
                                        @foreach($emotional_states as $emotional_state)
                                            <option value="{{ $emotional_state->id }}" @if( old('emotional_state_id') == $emotional_state->id ) selected="selected" @endif>
                                                {{ $emotional_state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Meeting Details -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Meeting Details</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" rows="10" name="meeting_details">{{ old('meeting_details') }}</textarea>
                                </div>
                            </div>

                            <hr class="panel-divider"/>

                            <div class="panel-subheading">Next Session</div>

                            <div class="alert alert-info" role="alert">
                                You can amend this later via the <a href="/calendar">Calendar</a>.
                            </div>

                            <!-- Next Planned Session Date -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover"
                                       data-content="Email reminders will be sent if a report has not been saved within three days of a planned session.">
                                    Date <i class="fas fa-info-circle"></i>
                                </label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="next_session_date" value="{{ old('next_session_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Next Planned Session Location -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Location</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="next_session_location" value="{{ old('next_session_location') }}">
                                </div>
                            </div>

                            <hr class="panel-divider"/>

                            <div class="panel-subheading">Leave (Holiday)</div>

                            <div class="alert alert-info" role="alert">
                                Fill in this section if you have some leave / time away to record for yourself or your mentee.<br/>
                                You can amend this later via the <a href="/calendar">Calendar</a>.
                            </div>

                            @include('mentor_leave.info_message')

                            <!-- Type of leave -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Leave Type</label>

                                <div class="col-md-6">
                                    <label class="radio-inline">
                                        <input class="leave-type-mentor" type="radio" name="leave_type" value="mentor" 
                                            @if( old('leave_type') == 'mentor' ) checked @elseif( old('leave_type') == null ) checked @endif>Mentor
                                    </label>
                                    <label class="radio-inline">
                                        <input class="leave-type-mentee" type="radio" name="leave_type" value="mentee"
                                            @if( old('leave_type') == 'mentee' ) checked @endif>Mentee
                                    </label>
                                </div>
                            </div>

                             <!-- Leave start date -->
                             <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover" 
                                    data-content="The first day of the leave.">
                                    Start Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="leave_start_date" value="{{ old('leave_start_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Leave end date -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover" 
                                       data-content="The last day (inclusive) of the leave.">
                                    End Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="leave_end_date" value="{{ old('leave_end_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Leave description -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Description</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="leave_description" value="{{ old('leave_description') }}">
                                </div>
                            </div>

                            <hr/>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" name="report_submit" class="btn btn-primary">
                                        <i class="fa m-r-xs fa-sign-in"></i>Submit
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Submitted Session Reports</div>
                    <ul class="list-group">
                        @foreach($reports as $report)
                            <li class="list-group-item">
                                {{ $report->mentee->name }}
                                <div class="pull-right">
                                    {{ $report->session_date->toFormattedDateString()  }}
                                </div>
                                <div class="clearfix"></div>
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
                placement: 'auto left'
            });
          });
    </script>

@endsection