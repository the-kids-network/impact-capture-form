@inject('sessionReportService', 'App\Domains\SessionReports\Services\SessionReportService')

@extends('layout.app')

@section('content')
    <div class="container expense-claim new">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">New Expense Claim</div>

                    <div class="card-body">
                        @include('shared.errors')

                        <form id="expense-claim-form" class="form-horizontal" role="form" method="POST" action="/expense-claim" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <!-- Related Session Report -->
                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label class="col-form-label" for="relatedSessionSelect">Related Session</label>
                                </div>
                                <div class="col-md-auto">
                                    <select id="relatedSessionSelect" class="form-control" name="report_id">
                                        @foreach($reports as $report)
                                            <option value="{{ $report->id }}">{{ $report->mentee->name }} on {{ $report->session_date->toFormattedDateString() }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Expense items -->
                            <div class="form-group">
                                <table class="table table-bordered" id="expense-form-table">
                                    <tr>
                                        <th class="w-25">Date</th>
                                        <th>Description</th>
                                        <th class="w-25">Amount</th>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control datepicker" name="expenses[1][date]"></td>
                                        <td><textarea class="form-control" rows="5" name="expenses[1][description]"></textarea></td>
                                        <td><input type="text" class="form-control" name="expenses[1][amount]"></td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Add and Delete Row Buttons -->
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <button id="add-row-button" type="button" class="btn btn-primary">
                                        <span class="fa fa-plus"></span> Add Row
                                    </button>
                                    <button id="delete-row-button" type="button" class="btn btn-danger">
                                        <span class="fa fa-minus"></span> Delete Row
                                    </button>
                                </div>
                            </div>

                            <!-- Upload Receipts -->
                            <hr class="card-divider"/>

                            <div class="form-group row">
                                <div class="col-md-2">
                                    <label id="receipts-label" for="receiptsInput">Upload Receipts</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" id="receiptsInput" name="receipts[]" multiple="multiple"></input>
                                </div>
                            </div>

                            <p class="help-block">You can upload multiple receipts. Only PDF and Image files can be uploaded.</p>

                            <!-- Submit Button -->
                            <hr class="card-divider"/>

                            <p>I certify that the above details are true and accurate and that I incurred the expenses wholly, necessarily and exclusively whilst engaged in TKN activity. 
                                I attach copies of true, itemised and accurate receipts for these claims.</p>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <span class="fas fa-paper-plane"></span> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Submitted Expense Claims</div>
                    <ul class="list-group list-group-flush">
                        @foreach($claims as $claim)
                            @php
                                $claimReport = $sessionReportService->getReport($claim->report_id)
                            @endphp
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-9">
                                        <span class="submitted-date">Claim for Session with {{ $claimReport->mentee->name }} on {{ $claimReport->session_date->toFormattedDateString() }} submitted on {{ $claim->created_at->toFormattedDateString() }}</span>
                                    </div>
                                    <div class="col-3">
                                        <span class="float-right text-capitalize font-weight-bolder">{{ $claim->status }}<span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        #expense-form-table textarea {
            resize: vertical;
        }
    </style>
@endsection

@section('body-scripts')
    <script>
        // Display a Date Picker for all the Dates to be input in the form
        $(function() {
            $(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });

            $('#add-row-button').click(function() {
                var number_rows = $('#expense-form-table tr').length;

                // New Row
                new_row = '<tr>'+
                        '<td>'+
                            '<input type="text" class="form-control datepicker" name="expenses[' + number_rows + '][date]">'+
                        '</td>'+
                        '<td>'+
                            '<textarea class="form-control" rows="5" name="expenses[' + number_rows + '][description]"></textarea>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" class="form-control" name="expenses[' + number_rows +'][amount]">'+
                        '</td>'+
                        '</tr>';

                $('#expense-form-table').append(new_row);
                $(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
            });

            $('#delete-row-button').click(function() {
                var number_rows = $('#expense-form-table tr').length;

                if (number_rows > 2){
                    $('#expense-form-table tr:last').remove();
                } else {
                    alert('Only 1 Row Left ...')
                }
            });
        });
    </script>
@endsection