@extends('layout.app')

@section('content')
    <div class="container mentor-reporting">
        <div class="row">
            <div class="col-md-12">

                <div class="card report-filter">
                    <div class="card-header">Filters</div>
                    <div class="card-body">
                        @include('shared.errors')
                        <form class="form-horizontal" role="form" method="GET" action="/reporting/mentor">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="startDateInput">Start Date</label>
                                <div class="col-md-6">
                                    <input id="startDateInput"
                                           type="text" 
                                           class="form-control datepicker" 
                                           name="start_date" 
                                           autocomplete="off" 
                                           value="{{ Request()->start_date }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="endDateInput">End Date</label>
                                <div class="col-md-6">
                                    <input id="endDateInput"
                                           type="text" 
                                           class="form-control datepicker" 
                                           name="end_date" 
                                           value="{{ Request()->end_date }}"
                                           autocomplete="off" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="showInactiveInput">Show Inactive</label>
                                <div class="col-md-6">
                                    <input  id="showInactiveInput"
                                            type="checkbox" 
                                            name="show_inactive"
                                            autocomplete="off"
                                            {{ (Request()->show_inactive) ? 'checked' : ''}} />
                                    </label>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <span class="fa fa-search"></span> Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>    
            
                @isset($mentors)
                <div class="card">
                    <div class="card-header">Report Data</div>
                    <div class="card-body">
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
                                            <a href={{ route('reports.get', [ 'mentor_id'=>$mentor->mentor_id ]) }}>
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
                                            <span class="d-none">{{ $mentor->start_date }}</span>
                                            @if (isset($mentor->start_date)) 
                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $mentor->start_date)->toFormattedDateString() }} 
                                            @else 
                                                Unknown 
                                            @endif
                                        </td>
                                        <td class="last-session-date">
                                            <span class="d-none">{{ $mentor->last_session_date }}</span>
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
                                            <span class="d-none">{{ $mentor->next_planned_session_date }}</span>
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

                        <a href="{{ route('mentor-reporting-export', 
                                    [ 'start_date' => Request()->start_date,
                                        'end_date' => Request()->end_date,
                                        'show_inactive' => Request()->show_inactive
                                    ]) 
                                    }}">Download All Data as CSV</a>
                    </div>
                </div>
                @endisset
            </div>
        </div>
    </div>
@endsection


@section('body-scripts')
    <script>
        $(function() {
            $(".datepicker").datepicker({ dateFormat: 'dd-mm-yy' });
        });
    </script>
@endsection
