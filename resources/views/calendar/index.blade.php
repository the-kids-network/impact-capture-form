@extends('layout.app' , ['body_class' => 'body-cal'])

@section('scripts')
    
@endsection

@section('content')
    <div class="calendar-page">
        <div class="calendar-header">
            <div class="new-report">
            @if (Auth::user()->isMentor())
                <a href="/report/new"><i class="fas fa-plus-circle"></i> New Session Report</a>
            @endif
            </div>

            <div class="new-event">
                <div class="new-planned-session">
                    <a href="/planned-session/new"><i class="fas fa-plus-circle"></i> New Planned Session</a>
                </div>
                <div class="new-mentor-leave">
                    <a href="/mentor/leave/new"><i class="fas fa-plus-circle"></i> New Leave</a>
                </div>
                <div class="new-mentee-leave">
                    <a href="/mentee/leave/new"><i class="fas fa-plus-circle"></i> New Leave (Mentee)</a>
                </div>
            </div>          
        </div>
        
        <div class="calendar-body">
            <calendar :events="{{ json_encode($events) }}" 
                    usertype="{{ (isset(Auth::user()->role)) ? Auth::user()->role : 'mentor'}}"
                    class="calendar" /> 
        </div>
    </div>
@endsection