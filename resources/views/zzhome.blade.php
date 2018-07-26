@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
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
                                            <option value="{{ $mentee->id }}">{{ $mentee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Date of Session -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Session Date</label>
                                <div class="col-md-6">
                                    <input type="date" class="form-control" name="session_date" value="{{ old('session_date') }}">
                                </div>
                            </div>

                            <!-- Length of Session -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Length of Session (hours)</label>
                                <div class="col-md-6">
                                    <input type="number" class="form-control" name="length_of_sessions" value="{{ old('length_of_sessions') }}">
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

                            <!-- Physical Appearance -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee's Physical Appearance</label>

                                <div class="col-md-6">
                                    <textarea class="form-control" rows="3" name="mentee_physical_appearance">{{ old('mentee_physical_appearance') }}</textarea>
                                </div>
                            </div>

                            <!-- Emotional State -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee's Emotional State</label>

                                <div class="col-md-6">
                                    <textarea class="form-control" rows="3" name="mentee_emotional_state">{{ old('mentee_emotional_state') }}</textarea>
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
                                    <button type="submit" class="btn btn-primary">
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
</home>
@endsection
