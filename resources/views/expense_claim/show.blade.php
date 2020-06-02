@inject('sessionReportService', 'App\Domains\SessionReports\Services\SessionReportService')

@php
    $expense_claim_report = $sessionReportService->getReport($expense_claim->report_id)
@endphp

@extends('layout.app')

@section('content')
    <div class="container expense-claim show">
        <div class="row">
            <div class="col-md-12">
                <nav class="nav page-nav">
                    <a class="nav-link" href="/expense-claim">Back to expense claims</a>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Expense Claim by {{ $expense_claim->mentor->name }}</div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>Field</th>
                                <th>Value</th>
                            </tr>
                            <tr class="claim-id">
                                <td class="label">Expense Claim ID</td>
                                <td class="value">{{ $expense_claim->id }}</td>
                            </tr>
                            <tr class="mentor-name">
                                <td class="label">Mentor Name</td>
                                <td class="value">{{ $expense_claim->mentor->name }}</td>
                            </tr>
                            <tr class="session">
                                <td class="label">Session</td>
                                <td class="value">
                                    <a href="{{ url('/report/'.$expense_claim->report_id) }}">With {{ $expense_claim_report->mentee->name }} on {{ $expense_claim_report->session_date->toFormattedDateString() }}</a>
                                </td>
                            </tr>
                            @if( $expense_claim->check_number && !Auth::user()->isMentor())
                            <tr class="finance-code">
                                <td class="label">Finance Code</td>
                                <td class="value">{{ $expense_claim->check_number }}</td>
                            </tr>
                            @endif
                            <tr class="status">
                                <td class="label">Status</td>
                                <td class="text-capitalize value">{{ $expense_claim->status }}</td>
                            </tr>
                        </table>

                        <table class="table" id="expenses-table">
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>

                            @foreach($expense_claim->expenses as $expense)
                            <tr>
                                <td class="expense-date">{{ $expense->date->toFormattedDateString() }}</td>
                                <td class="expense-description">{{ $expense->description }}</td>
                                <td class="expense-amount">{{ $expense->amount }}</td>
                            </tr>
                            @endforeach
                        </table>

                        @if( count($expense_claim->receipts) > 0)
                        <table class="table" id="receipts-table">
                            <tr>
                                <th colspan="2">Receipts (Click on the image(s) to download)</th>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    @foreach($expense_claim->receipts as $receipt)
                                        <a class="receipt-link" href="{{ url('/receipt/' . $receipt->id) }}"><img class="preview-receipt" width="100" height="100" src="{{ url('/receipt/' . $receipt->id) }}"></a>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                        @else
                        <div>
                            No Receipts uploaded
                        </div>
                        @endif

                        @if($expense_claim->status == 'pending' && Auth::user()->isAdmin())
                        @include('shared.errors')
                        <form id="process-form" class="form-horizontal" role="form" method="post" action="{{url('/expense-claim/'.$expense_claim->id)}}">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}

                            <!-- Check Number -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="codeInput">Finance Code (Optional)</label>
                                <div class="col-md-6">
                                    <input id="codeInput" type="text" class="form-control" name="check_number" value="{{ old('check_number') }}" autofocus>
                                </div>
                            </div>

                            <!-- Process Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-3">
                                    <button type="submit" class="btn btn-primary" name="status" value="processed">
                                        <span class="fa fa-credit-card"></span> Process Payment
                                    </button>
                                    <button type="submit" class="btn btn-danger" name="status" value="rejected">
                                        Reject Expense Claim
                                    </button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    <div class="card-footer">
                        @if($expense_claim->status == 'rejected' || $expense_claim->status == 'processed')
                            @if(Auth::user()->isMentor())
                            This claim has been {{$expense_claim->status}} 
                            @else
                            This claim has been {{$expense_claim->status}} by {{ $expense_claim->processedBy->name }}.
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .preview-receipt {
            margin: 5px;
        }

        #expenses-table {
            margin-top: 15px;
        }

        #receipts-table {
            margin-top: 15px;
        }
    </style>
@endsection