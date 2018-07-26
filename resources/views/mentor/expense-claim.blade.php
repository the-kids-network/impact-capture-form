@extends('spark::layouts.app')

@section('content')
{{--<home :user="user" inline-template>--}}
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">New Expense Claim</div>

                    <div class="panel-body">
                        @include('spark::shared.errors')

                        <form id="expense-claim-form" class="form-horizontal" role="form" method="POST" action="/expense-claim" enctype="multipart/form-data">
                        {{ csrf_field() }}

                            <!-- Related Session Report -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Related Session</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="report_id">
                                        @foreach($reports as $report)
                                            <option value="{{ $report->id }}">{{ $report->mentee->first_name }} on {{ $report->session_date->toFormattedDateString() }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <table class="table table-bordered" id="expense-form-table">
                                <tr>
                                    <th id="date-column">Date</th>
                                    <th id="description-column">Description</th>
                                    <th id="amount-column">Amount</th>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="text" class="form-control datepicker" name="expenses[1][date]">
                                    </td>
                                    <td>
                                        <textarea class="form-control" rows="5" name="expenses[1][description]"></textarea>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="expenses[1][amount]">
                                    </td>
                                </tr>
                            </table>

                            <!-- Add and Delete Row Buttons -->
                            <div class="form-group">
                                <div class="col-md-8">
                                    <button id="add-row-button" type="button" class="btn btn-default">
                                        <i class="fa m-r-xs fa-plus"></i>Add Row
                                    </button>

                                    <button id="delete-row-button" type="button" class="btn btn-danger">
                                        <i class="fa m-r-xs fa-minus"></i>Delete Row
                                    </button>
                                </div>
                            </div>

                            <!-- Upload Receipts -->
                            <div class="form-group">
                                <div class="col-md-12">
                                    <label id="receipts-label" for="receipts">Upload Receipts</label>
                                    <input type="file" id="receipts" name="receipts[]" multiple="multiple"></input>
                                    <p class="help-block">You can upload multiple receipts. Only PDF and Image files can be uploaded.</p>
                                </div>
                            </div>


                            <!-- Submit Button -->
                            <p>
                                I certify that the above details are true and accurate and that I incurred the expenses wholly, necessarily and exclusively whilst engaged in TKN activity. I attach copies of true, itemised and accurate receipts for these claims.
                            </p>

                            <div class="form-group">
                                <div class="col-md-8">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa m-r-xs fa-sign-in"></i>Submit
                                    </button>
                                </div>
                            </div>



                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Submitted Expense Claims</div>
                    <ul class="list-group">
                        @foreach($claims as $claim)
                            <li class="list-group-item">
                                <span class="submitted-date">Claim for Session with {{ $claim->report->mentee->first_name }} on {{ $claim->report->session_date->toFormattedDateString() }} submitted on {{ $claim->created_at->toFormattedDateString() }}</span>
                                <div class="pull-right text-capitalize">
                                    {{ $claim->status }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

{{--</home>--}}
@endsection


@section('scripts')
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">

    <style>
        #date-column{
            width: 15%;
        }

        #description-column{
            width: 70%;
        }

        #amount-column{
            width: 15%;
        }

        #expense-form-table textarea{
            resize: vertical;
        }

    </style>
@endsection


@section('body-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

    <script>
        // Display a Date Picker for all the Dates to be input in the form
        $( function() {
            $( ".datepicker" ).datepicker();
        } );

        $( function(){
            $('#add-row-button').click( function(){

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

                $('#expense-form-table').append( new_row );
                $( ".datepicker" ).datepicker();


            });

            $('#delete-row-button').click( function(){

                var number_rows = $('#expense-form-table tr').length;

                if(number_rows > 2){
                    $('#expense-form-table tr:last').remove();
                } else{
                    alert('Only 1 Row Left ...')
                }

            });

        });
    </script>


@endsection