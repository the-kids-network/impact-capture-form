@extends('spark::layouts.app')

@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-credit-card text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/manager/view-expense-claims') }}">View Expense Claims</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-book text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/report') }}">View Session Reports</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-download text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/report/export') }}">Download Session Reports</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-download text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/manager/expense-claim/export') }}">Download Expense Claims</a>
                    </div>
                </div>
            </div>

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

@endsection

@section('scripts')
    <style>
        .text-feature{
            font-size: 80px;
        }
    </style>
@endsection