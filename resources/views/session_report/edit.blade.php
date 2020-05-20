@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                <div class="panel-heading">Edit Session Report: {{$report->id}}</div>
                    <div class="panel-body">
                        <session-report-editor 
                            :report="{{ json_encode($report) }}"
                            :activity-types-lookup="{{ json_encode($activity_types) }}"
                            :emotional-states-lookup="{{ json_encode($emotional_states) }}"
                            :ratings-lookup="{{ json_encode($session_ratings) }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection