@extends('layout.app' , ['body_class' => 'body-cal'])

@section('content')
    <div class="calendar container">
        <div class="row">
            <div class="col-md-12">
                <div id="calendar-info" class="calendar-info alert alert-info alert-dismissible fade show" 
                    role="alert" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p><b>Welcome to the Calendar!</b> From here you can create and change planned sessions and leave.
                            <br/><br/>
                            Click on an event in the calendar to change/delete it. Or use the links on the right to add a new event.
                        </p>
                </div>
            </div>  
        </div>
        <div class="row">
            <div class="calendar-header col-md-12">
                <div class="row">
                    <div class="links-group left-links col-md">
                    @if (Auth::user()->isMentor())
                        <a class="new-report" href="/report/new"><i class="fas fa-plus-circle"></i> New Session Report</a>
                    @endif
                    </div>

                    <div class="links-group right-links col-md-4">
                        <div class="row">
                            <div class="col-4 col-md-12 text-md-right">
                                <a class="new-planned-session" href="/planned-session/new"><i class="fas fa-plus-circle"></i> New Planned Session</a>
                            </div>
                            <div class="col-4 col-md-12 text-md-right">
                                <a class="new-mentor-leave" href="/mentor/leave/new"><i class="fas fa-plus-circle"></i> New Leave - Mentor</a>
                            </div>
                            <div class="col-4 col-md-12 text-md-right">
                                <a class="new-mentee-leave" href="/mentee/leave/new"><i class="fas fa-plus-circle"></i> New Leave - Mentee</a>
                            </div>
                        </div>  
                    </div>
                </div>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="calendar-body">
                    <calendar :events="{{ json_encode($events) }}" 
                            usertype="{{ (isset(Auth::user()->role)) ? Auth::user()->role : 'mentor'}}"
                            class="calendar" /> 
                </div>
            </div>
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