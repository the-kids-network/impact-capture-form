@inject('sessionReportService', 'App\Domains\SessionReports\Services\SessionReportService')

@php
    $expense_claim_report = $sessionReportService->getReport($expense_claim->report_id)
@endphp

@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Expense Claim by {{ $expense_claim->mentor->name }}</div>
                    <table class="table">
                        <tr>
                            <th class="col-xs-4">Field</th>
                            <th class="col-xs-8">Value</th>
                        </tr>
                        <tr>
                            <td>Mentor Name</td>
                            <td>{{ $expense_claim->mentor->name }}</td>
                        </tr>
                        <tr>
                            <td>Session</td>
                            <td>
                                <a href="{{ url('/report/'.$expense_claim->report_id) }}">With {{ $expense_claim_report->mentee->name }} on {{ $expense_claim_report->session_date->toFormattedDateString() }}</a>
                            </td>
                        </tr>
                        @if( $expense_claim->check_number )
                        <tr>
                            <td>Finance Code</td>
                            <td>{{ $expense_claim->check_number }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td>Status</td>
                            <td class="text-capitalize">{{ $expense_claim->status }}</td>
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
                            <td>{{ $expense->date->toFormattedDateString() }}</td>
                            <td>{{ $expense->description }}</td>
                            <td>{{ $expense->amount }}</td>
                        </tr>
                        @endforeach
                    </table>

                    @if( count($expense_claim->receipts) > 0)
                    <table class="table" id="receipts-table">
                        <tr>
                            <th colspan="2">Receipts (Click on the Images to Download)</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                @foreach($expense_claim->receipts as $receipt)
                                    <a href="{{ url('/receipt/' . $receipt->id) }}"><img class="preview-receipt" width="100" height="100" src="{{ url('/receipt/' . $receipt->id) }}"></a>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                    @else
                        <div class="panel-body">
                            No Receipts uploaded
                        </div>
                    @endif

                    @if($expense_claim->status == 'pending' && Auth::user()->isAdmin())
                        <div class="panel-body">
                            @include('shared.errors')

                            <form id="process-form" class="form-horizontal" role="form" method="post" action="{{url('/expense-claim/'.$expense_claim->id)}}">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}

                                <!-- Check Number -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Finance Code (Optional)</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="check_number" value="{{ old('check_number') }}" autofocus>
                                    </div>
                                </div>

                                <!-- Process Button -->
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary" name="status" value="processed">
                                            <i class="fa m-r-xs fa-credit-card"></i>Process Payment
                                        </button>
                                        <button type="submit" class="btn btn-danger" name="status" value="rejected">
                                            Reject Expense Claim
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    <div class="panel-footer">
                        @if($expense_claim->status == 'rejected' || $expense_claim->status == 'processed')
                            This claim has been {{$expense_claim->status}} by {{ $expense_claim->processedBy->name }}.
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .preview-receipt{
            margin: 5px;
        }

        #expenses-table{
            margin-top: 15px;
        }

        #receipts-table{
            margin-top: 15px;
        }
    </style>
@endsection