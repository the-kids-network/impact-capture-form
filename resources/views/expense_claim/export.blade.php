@extends('spark::layouts.app')

@section('content')
    <div class="container">

        <div class="row m-b-lg">
            <div class="col-md-12">
                <button class="btn btn-lg btn-primary btn-block" onclick="exportTableToCSV('data.csv')">Click to Download Data as CSV</button>
            </div>
        </div>

        <div class="row m-t-lg m-b-lg">
            <div class="col-md-12">
                <a class="btn btn-lg btn-primary btn-block" href="{{ url('/receipt/download-all') }}">Click to Download Receipts</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                    <table id="test-table" class="table table-bordered table-responsive table-striped">
                    <tr>
                        <th>Claim ID</th>
                        <th>Report or Session ID</th>
                        <th>Mentor Name</th>
                        <th>Expense Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Finance Code</th>
                        <th>Created On</th>
                    </tr>

                    @foreach($expense_claims as $expense_claim)
                        @foreach($expense_claim->expenses as $expense)
                            <tr>
                                <td>{{ $expense_claim->id }}</td>
                                <td>{{ $expense_claim->report_id }}</td>
                                <td>{{ $expense_claim->mentor->name }}</td>
                                <td>{{ $expense->date->format('m-d-y') }}</td>
                                <td>{{ $expense->description }}</td>
                                <td>{{ $expense->amount }}</td>
                                <td>{{ $expense_claim->status }}</td>
                                <td>{{ $expense_claim->check_number }}</td>
                                <td>{{ $expense_claim->created_at->toFormattedDateString() }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@section('body-scripts')
    <script src="/js/jquery.TableCSVExport.js"></script>

    <script>
        function exportTableToCSV(filename){
            $(document).ready(function() {
                $('#test-table').TableCSVExport({
                    delivery: 'download',
                    filename: filename
                });
            });
        }
    </script>
@endsection