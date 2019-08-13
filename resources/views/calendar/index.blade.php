@extends('layout.app' , ['body_class' => 'calendar-body'])

@section('scripts')
    
@endsection

@section('content')
    <div>
        <span class="calendar-header">
            @if (Auth::user()->isMentor())
                <div class="calendar-new-report">
                    <a href="/report/new"><i class="fas fa-plus-circle"></i> New Session Report</a>
                </div>
            @endif

            <div class="calendar-new-planned-session">
                <a href="/planned-session/new"><i class="fas fa-plus-circle"></i> New Planned Session</a>
            </div>

            <div class="calendar-new-leave">
                <a href="/mentor/leave/new"><i class="fas fa-plus-circle"></i> New Leave</a>
            </div>
        </span>
        <br/>
        <br/>

        <calendar :events="{{ json_encode($events) }}" 
                  usertype="{{ (isset(Auth::user()->role)) ? Auth::user()->role : 'mentor'}}"
                  class="calendar" /> 
    </div>
@endsection