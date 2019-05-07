@extends('spark::layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Filters</div>

                    <div class="panel-body">
                        @include('spark::shared.errors')

                        <form class="form-horizontal" role="form" method="GET" action="/reporting/generate">
                        {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-4 control-label">Start Date</label>
                                <div class="col-md-6">
                                    <input type="text" 
                                           class="form-control datepicker" 
                                           name="start_date" 
                                           autocomplete="off" 
                                           value="{{ app('request')->input('start_date') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">End Date</label>
                                <div class="col-md-6">
                                    <input type="text" 
                                           class="form-control datepicker" 
                                           name="end_date" 
                                           value="{{ app('request')->input('end_date') }}"
                                           autocomplete="off" >
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa m-r-xs fa-sign-in"></i>Submit
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>    
            </div>
            @isset($mentors)
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Result</div>
                        <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Mentor ID</th>
                                    <th data-sortable="true">Mentor Name</th>
                                    <th data-sortable="true">Session Count</th>
                                    <th data-sortable="true">Total Hours</th>

                                </tr>
                            </thead>

                            <tbody>
                                @foreach($mentors as $mentor)
                                    <tr>
                                        <td class="mentor-id">{{ $mentor->mentor_id }}</td>
                                        <td class="mentor-name">{{ $mentor->mentor_name }}</td>
                                        <td class="session-count">{{ $mentor->session_count }}</td>
                                        <td class="total-hours">{{ $mentor->total_hours }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                        <div class="panel-body">
                                <a href="{{ url('reporting/export') }}">Download All Data as CSV</a>
                        </div>

                    </div>
                </div>
            </div>
            @endisset
        </div>
    </div>

@endsection

@section('scripts')
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
@endsection

@section('body-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>

    <script>
        $( function() {
            $( ".datepicker" ).datepicker();
        } );
    </script>
@endsection
