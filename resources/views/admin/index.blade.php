@extends('layout.app')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <h3>Modify Session Report Form</h3>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-child text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/mentee') }}">Mentee</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-bicycle text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/activity-type') }}">Activity Types</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-eye text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/physical-appearance') }}">Physical Appearance</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-smile text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/emotional-state') }}">Emotional State</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>View / Export Data</h3>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-download text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/report') }}">Session Reports</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-download text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/expense-claim') }}">Expense Claims</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-calendar-alt text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/calendar') }}">Calendar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Reporting</h3>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-chart-line text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/reporting/mentor') }}">Mentor Reporting</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Manage Roles</h3>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-user text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/roles/mentor') }}">Mentor</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-male text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/roles/manager') }}">Manager</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-lock text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/roles/admin') }}">Admin</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-user text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/register') }}">Register New User</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Finance</h3>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-credit-card text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/finance/process-expense-claims') }}">Process Expense Claims</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-download text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/finance/expense-claim/export') }}">Download Expense Claims</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Delete</h3>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-trash-alt text-feature"></i>
                        {{--<a class="btn btn-danger btn-block btn-lg m-t-lg" href="{{ url('/roles/manager') }}">Delete all Reports and Expense Claims</a>--}}

                        <form action="/delete-all" method="post">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <input type="button" value="Delete all Session Reports and Expense Claims" class="btn btn-lg m-t-lg btn-danger" 
                                   data-toggle="modal" data-target="#delete-confirmation">

                            <div class="modal fade" id="delete-confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Confirm deletion</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to erase all data in the system such as all past session reports and expenses?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                            <input type="submit" value="Delete" class="btn btn-secondary"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <style>
        .text-feature{
            font-size: 80px;
        }
    </style>
@endsection