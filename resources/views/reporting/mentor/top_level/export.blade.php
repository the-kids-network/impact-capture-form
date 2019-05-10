@extends('spark::layouts.app')

@section('content')

    <div class="container">

        <div class="row m-b-lg">
            <div class="col-md-12">
                <button class="btn btn-lg btn-primary btn-block" onclick="exportTableToCSV('data.csv')">Click to Download Data as CSV</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table id="data-table" class="table table-bordered table-responsive table-striped">
                    <tr>
                        <th>Mentor Name</th>
                        <th>First Session Date</th>
                        <th>Expected Sessions</th>
                        <th>Actual Sessions</th>
                        <th>Total Session Length (Hrs)</th>
                        <th>Total Expense Claims (£)</th>
                    </tr>

                    @foreach($mentors as $mentor)
                    <tr>
                        <td class="mentor-name">{{ $mentor->user_name }}</td>
                        <td class="start-date">
                            @if (isset($mentor->first_session_date)) 
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $mentor->first_session_date)->format('d-m-y') }} 
                            @else 
                                Unknown 
                            @endif
                        </td>
                        <td class="expected-session-count">
                            @if (isset($mentor->expected_session_count)) {{ $mentor->expected_session_count }} 
                            @else Unknown 
                            @endif
                        </td>
                        <td class="total-session-count">{{ $mentor->session_count }}</td>
                        <td class="total-session-length">{{ $mentor->session_length }}</td>
                        <td class="expenses-total">{{ $mentor->expenses_total }}</td>

                    </tr>
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
                $('#data-table').TableCSVExport({
                    delivery: 'download',
                    filename: filename
                });
            });
        }
    </script>

@endsection