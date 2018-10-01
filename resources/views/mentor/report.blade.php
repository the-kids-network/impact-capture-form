@extends('spark::layouts.app')

@section('content')
{{--<home :user="user" inline-template>--}}
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Weekly Session Report</div>

                    <div class="panel-body">
                        @include('spark::shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/report">
                        {{ csrf_field() }}

                            <!-- Mentee's Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="mentee_id">
                                        @foreach($mentees as $mentee)
                                            <option value="{{ $mentee->id }}">{{ $mentee->first_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Date of Session -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Session Date</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="session_date" value="{{ old('session_date') }}">
                                </div>
                            </div>

                            <!-- Session Rating -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover" data-content="{!! 'These could relate to the chosen activity, your mentee’s emotional state during the session or the outcomes of the session.' !!}">Please rate your session <i class="fas fa-info-circle"></i></label>
                                <div class="col-md-6">
                                    <select class="form-control" name="rating_id">
                                        @foreach($session_ratings as $rating)
                                            <option value="{{ $rating->id }}">{{ $rating->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Length of Session -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Length of Session (hours)</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="length_of_session" value="{{ old('length_of_sessions') }}">
                                </div>
                            </div>

                            <!-- Type of Activity -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Activity Type</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="activity_type_id">
                                        @foreach($activity_types as $activity_type)
                                            <option value="{{ $activity_type->id }}">{{ $activity_type->name }}</option>
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
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Mentee's Physical Appearance -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee's Physical Appearance</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="physical_appearance_id">
                                        @foreach($physical_appearances as $physical_appearance)
                                            <option value="{{ $physical_appearance->id }}">{{ $physical_appearance->name }}</option>
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
                                            <option value="{{ $emotional_state->id }}">{{ $emotional_state->name }}</option>
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
                                {{ $report->mentee->first_name }}
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

{{--</home>--}}
@endsection


@section('scripts')
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
@endsection


@section('body-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

    <script>
        $( function() {
            $( ".datepicker" ).datepicker();
        } );

        $(document).ready(function() {
            $('[data-toggle="popover"]').popover({
              html: true,
              placement: 'auto left'
            });
          });
    </script>

@endsection