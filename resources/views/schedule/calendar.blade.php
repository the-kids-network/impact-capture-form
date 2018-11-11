@extends('spark::layouts.app')

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>

@endsection

@section('content')
    <div>
        <span class="calendar-header">
            @unless ($user->isAdmin())
                <div class="calendar-new-report">
                    <a href="/my-reports"><i class="fas fa-plus-circle"></i> New Session Report</a>
                </div>
            @endunless

            <div class="calendar-new-schedule">
                <a href="/schedule"><i class="fas fa-plus-circle"></i> Schedule New Session</a>
            </div>
        </span>
        <br/>
        <br/>
        <div class="calendar">
            {!! $calendar->calendar() !!}
            {!! $calendar->script() !!}
        </div>
    </div>
@endsection