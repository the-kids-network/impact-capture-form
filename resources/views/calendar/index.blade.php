@extends('layout.app' , ['body_class' => 'body-cal'])

@section('content')
    <div class="calendar-page">
        <div id="calendar-info" class="alert alert-info alert-dismissible fade in" role="alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <p><b>Welcome to the Calendar!</b> From here you can create and change planned sessions and leave.
                    <br/><br/>
                    Click on an event in the calendar to change/delete it. Or use the links on the right to add a new event.
                </p>
        </div>
        
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
                    <a href="/mentor/leave/new"><i class="fas fa-plus-circle"></i> New Leave - Mentor</a>
                </div>
                <div class="new-mentee-leave">
                    <a href="/mentee/leave/new"><i class="fas fa-plus-circle"></i> New Leave - Mentee</a>
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

@section('body-scripts')
    <script>
        $(document).ready(function() {
            $('#calendar-info').on('close.bs.alert', function () {
                localStorage.setItem('tkndismissedCalendarInfo', true)
            })

            if (localStorage.tkndismissedCalendarInfo === undefined || localStorage.tkndismissedCalendarInfo == 'false') {
                $('#calendar-info').show();
            }
        })
    </script>
@endsection