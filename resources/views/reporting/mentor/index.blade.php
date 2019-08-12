@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Filters</div>

                    <div class="panel-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="GET" action="/reporting/mentor">
                        {{ csrf_field() }}

                            <div class="form-group">
                                <label class="col-md-4 control-label">Start Date</label>
                                <div class="col-md-6">
                                    <input type="text" 
                                           class="form-control datepicker" 
                                           name="start_date" 
                                           autocomplete="off" 
                                           value="{{ Request()->start_date }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">End Date</label>
                                <div class="col-md-6">
                                    <input type="text" 
                                           class="form-control datepicker" 
                                           name="end_date" 
                                           value="{{ Request()->end_date }}"
                                           autocomplete="off" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Show Inactive</label>
                                <div class="col-md-6">
                                    <input type="checkbox" 
                                            name="show_inactive"
                                            autocomplete="off"
                                            {{ (Request()->show_inactive) ? 'checked' : ''}} />
                                    </label>
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
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Report Data</div>
                        <table class="table" data-toggle="table" data-search="true" data-pagination="true">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Mentor Name</th>
                                    @if(Request()->show_inactive)
                                    <th data-sortable="true">Active</th>
                                    @endif
                                    @if( !Auth::user()->isManager() )
                                    <th data-sortable="true">Manager Name</th>
                                    @endif
                                    <th data-sortable="true">Start Date</th>
                                    <th data-sortable="true">Last Session Date</th>
                                    <th data-sortable="true">Days Since Last Session</th>
                                    <th data-sortable="true">Next Planned Session</th>
                                    <th data-sortable="true">Expected Sessions</th>
                                    <th data-sortable="true">Actual Sessions</th>
                                    <th data-sortable="true">Total Session Length (Hrs)</th>
                                    <th data-sortable="true">Expenses Total (£)</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($mentors as $mentor)
                                    <tr>
                                        <td class="mentor-name">
                                            <a href={{ route('report.index', [ 'mentor_id'=>$mentor->mentor_id ]) }}>
                                                    {{ $mentor->mentor_name }}
                                            </a>
                                        </td>
                                        @if( Request()->show_inactive )
                                        <td class="mentor-active">
                                            {{ ($mentor->active) ? 'Yes' : 'No' }}
                                        </td>
                                        @endif
                                        @if( !Auth::user()->isManager() )
                                        <td class="manager-name">
                                            @if (isset($mentor->manager_name )) {{ $mentor->manager_name }}  @else Unassigned @endif
                                        </td>
                                        @endif
                                        <td class="start-date">
                                            <span class="hidden">{{ $mentor->start_date }}</span>
                                            @if (isset($mentor->start_date)) 
                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $mentor->start_date)->toFormattedDateString() }} 
                                            @else 
                                                Unknown 
                                            @endif
                                        </td>
                                        <td class="last-session-date">
                                            <span class="hidden">{{ $mentor->last_session_date }}</span>
                                            @if (isset($mentor->last_session_date)) 
                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $mentor->last_session_date)->toFormattedDateString() }} 
                                            @else 
                                                Unknown 
                                            @endif
                                        </td>
                                        <td class="days-since-last-session">
                                            @if (isset($mentor->days_since_last_session)) {{ $mentor->days_since_last_session}} @else Unknown @endif
                                        </td>
                                        <td class="next-planned-session-date">
                                            <span class="hidden">{{ $mentor->next_planned_session_date }}</span>
                                            @if (isset($mentor->next_planned_session_date)) 
                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $mentor->next_planned_session_date)->toFormattedDateString() }} 
                                            @else 
                                                Unknown 
                                            @endif
                                        </td>
                                        <td class="expected-session-count">
                                            @if (isset($mentor->expected_session_count)) {{ $mentor->expected_session_count }} @else Unknown @endif
                                        </td>
                                        <td class="actual-session-count">{{ $mentor->session_count }}</td>
                                        <td class="total-session-length">{{ $mentor->session_length }}</td>
                                        <td class="expenses-total">
                                            <a href={{ route('expense-claim.index', [ 'mentor_id'=>$mentor->mentor_id ]) }}>
                                                {{ $mentor->expenses_total }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="panel-body">
                            <a href="{{ route('mentor-reporting-export', 
                                        [ 'start_date' => Request()->start_date,
                                            'end_date' => Request()->end_date,
                                            'show_inactive' => Request()->show_inactive
                                        ]) 
                                        }}">Download All Data as CSV</a>
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
            $( ".datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
        } );
    </script>
@endsection
