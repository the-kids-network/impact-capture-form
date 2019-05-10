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
                        <th>Total (£)</th>
                        <th>Pending (£)</th>
                        <th>Approved (£)</th>
                        <th>Rejected (£)</th>
                    </tr>

                    @foreach($mentors as $mentor)
                    <tr>
                        <td class="mentor-name">{{ $mentor->user_name }}</td>
                        <td class="expenses-total">{{ $mentor->expenses_total }}</td>
                        <td class="expenses-pending">{{ $mentor->expenses_pending }}</td>
                        <td class="expenses-approved">{{ $mentor->expenses_approved}}</td>
                        <td class="expenses-rejected">{{ $mentor->expenses_rejected}}</td>
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