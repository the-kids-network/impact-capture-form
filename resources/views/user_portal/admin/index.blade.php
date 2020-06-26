@extends('layout.app')

@section('content')
    <div class="container user-portal admin">
        <div class="row">
            <div class="col-md-12">
                <h3>Main</h3>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fab fa-searchengin text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg v2-session-reports" href="{{ url('/session-reports') }}">
                            <span class="fas fa-sun"></span> Session Reports V2 <span class="fas fa-sun"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-search text-feature"></i>
                        <a class="btn btn-secondary btn-block btn-lg v1-session-reports" href="{{ url('/report') }}">Session Reports V1 (Old)</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-calendar-alt text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/calendar') }}">Calendar</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Expenses</h3>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fas fa-search-dollar text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg v2-expenses" href="{{ url('/expenses') }}">
                            <span class="fas fa-sun"></span> Expenses V2 <span class="fas fa-sun"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-credit-card text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="app#/expenses/search?status=Pending">Pending Expense Claims</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fas fa-search-dollar text-feature"></i>
                        <a class="btn btn-secondary btn-block btn-lg" href="{{ url('/expense-claim') }}">Expenses V1 (Old)</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Document Sharing</h3>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-cloud-upload-alt text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/documents/upload/index') }}">Upload</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-folder-open text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/documents/index') }}">Browse & Manage</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Funding</h3>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fab fa-gratipay text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/funders') }}">Funders</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-money-bill-alt text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/fundings') }}">Mentor Funding</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Reporting</h3>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-chart-line text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/reporting/mentor') }}">Mentor Reporting</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row user-management">
            <div class="col-md-12">
                <h3>Manage Users</h3>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-child text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/mentees') }}">Mentee</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-user text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/user-management/mentors') }}">Mentor</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-male text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/user-management/managers') }}">Manager</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-lock text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/user-management/admins') }}">Admin</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-user text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/register') }}">Register New User</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Modify Session Report Form</h3>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-bicycle text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/activity-types') }}">Activity Types</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card link-panel">
                    <div class="card-body text-center">
                        <i class="fa fa-smile text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg" href="{{ url('/emotional-states') }}">Emotional State</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection