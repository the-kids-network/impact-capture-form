@extends('spark::layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-child text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/my-reports') }}">Submit a Session Report</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-body text-center">
                        <i class="fa fa-credit-card text-feature"></i>
                        <a class="btn btn-primary btn-block btn-lg m-t-lg" href="{{ url('/my-expense-claims') }}">Submit an Expense Claim</a>
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