@extends('layout.app')

@section('content')
    <div class="container user-portal mentor">
        <div class="row">
            <div class="col-md-6">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-child text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/report/new') }}">Submit a Session Report</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-book text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/session-reports') }}">View Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-receipt text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/expense-claim/new') }}">Submit an Expense Claim</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-credit-card text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/expenses') }}">View Expense Claims</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-calendar-alt text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/calendar') }}">Calendar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-calendar-alt text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/planned-session/next') }}">Change Next Planned Session</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-folder-open text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/documents/index') }}">Browse Documents</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection