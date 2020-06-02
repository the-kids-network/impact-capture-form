@extends('layout.app')

@section('content')
    <div class="container session-report edit">
        <div class="row">
            <div class="col-md-12">
                <nav class="nav page-nav">
                    <a class="nav-link" href="/report/{{ $report->id }}">Back to view report</a>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                <div class="card-header">Edit Session Report: {{$report->id}}</div>
                    <div class="card-body">
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