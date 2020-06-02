@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <button class="btn btn-lg btn-primary btn-block" onclick="exportTableToCSV('funding-data.csv')">Click to Download Data as CSV</button>
                <br/>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="test-table" class="table table-bordered table-responsive table-striped" style="width: 100%">
                    <tr>
                        <th>Mentor</th>
                        <th>Funder</th>
                        <th>Year</th>
                    </tr>

                    @foreach($fundings as $funding)
                    <tr>
                        <td class="mentor">
                            {{$funding->mentor->name}}
                        </td>
                        <td class="funder">
                            {{$funding->funder->code}}
                        </td>
                        <td class="year">
                            {{$funding->funding_year}}
                        </td>
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
                $('#test-table').TableCSVExport({
                    delivery: 'download',
                    filename: filename
                });
            });
        }
    </script>
@endsection