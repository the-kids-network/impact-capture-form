@extends('layout.app')

@section('content')
    <div class="user-portal manager">
        <div class="container">
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
                            <i class="fas fa-search text-feature"></i>
                            <a class="btn btn-secondary btn-block btn-lg v1-session-reports" href="{{ url('/report') }}">Session Reports V1 (Old)</a>
                        </div>
                    </div>
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
                            <i class="fas fa-search-dollar  text-feature"></i>
                            <a class="btn btn-secondary btn-block btn-lg" href="{{ url('/expense-claim') }}">Expenses V1 (Old)</a>
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
        </div>
    </div>
@endsection