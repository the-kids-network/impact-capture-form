@extends('spark::layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" id="expense-claim-list">
                    <div class="panel-heading">Submitted Expense Claims</div>
                    <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                            <tr>
                                <th data-sortable="true">Claim ID</th>
                                <th data-sortable="true">Mentor Name</th>
                                <th data-sortable="true">Session</th>
                                <th data-sortable="true">Created On</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($mentors as $mentor)
                                @foreach($mentor->expense_claims as $claim)
                                    @if($claim->status == 'pending')
                                    <tr class="clickable-row" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                        <td>{{ $claim->id }}</td>
                                        <td>{{ $claim->mentor->name }}</td>
                                        <td>With {{ $claim->report->mentee->first_name . ' ' . $claim->report->mentee->last_name }} on {{ $claim->report->session_date->toFormattedDateString() }}</td>
                                        <td>{{ $claim->created_at->toFormattedDateString() }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>



    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default" id="approved-expense-claim-list">
                    <div class="panel-heading">Approved or Rejected Expense Claims</div>
                    <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                        <thead>
                        <tr>
                            <th data-sortable="true">Claim ID</th>
                            <th data-sortable="true">Mentor Name</th>
                            <th data-sortable="true">Session</th>
                            <th data-sortable="true">Created On</th>
                            <th data-sortable="true">Status</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($mentors as $mentor)
                            @foreach($mentor->expense_claims as $claim)
                                @if($claim->status != 'pending')
                                <tr class="clickable-row" data-href="{{ url('/expense-claim/'.$claim->id) }}">
                                    <td>{{ $claim->id }}</td>
                                    <td>{{ $claim->mentor->name }}</td>
                                    <td>With {{ $claim->report->mentee->first_name . ' ' . $claim->report->mentee->last_name }} on {{ $claim->report->session_date->toFormattedDateString() }}</td>
                                    <td>{{ $claim->created_at->toFormattedDateString() }}</td>
                                    <td class="text-capitalize">{{ $claim->status }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>




@endsection

@section('scripts')

    <style>
        .clickable-row{
            cursor: pointer;
        }
    </style>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
@endsection



@section('body-scripts')

    <!-- Latest compiled and minified JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>

    <script>
        jQuery(document).ready(function($) {
            $(".clickable-row").click(function() {
                window.location = $(this).data("href");
            });
        });
    </script>
@endsection