@extends('layout.app')

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
                        @if(Request()->show_inactive)
                        <th data-sortable="true">Active</th>
                        @endif
                        @if( !Auth::user()->isManager() )
                        <th data-sortable="true">Manager Name</th>
                        @endif
                        <th>Start Date</th>
                        <th data-sortable="true">Last Session Date</th>
                        <th data-sortable="true">Days Since Last Session</th>
                        <th data-sortable="true">Next Planned Session</th>
                        <th>Expected Sessions</th>
                        <th>Actual Sessions</th>
                        <th>Total Session Length (Hrs)</th>
                        <th>Expenses Total (£)</th>
                    </tr>

                    @foreach($mentors as $mentor)
                    <tr>
                        <td class="mentor-name">{{ $mentor->mentor_name }}</td>
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
                            @if (isset($mentor->start_date)) 
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $mentor->start_date)->format('d-m-Y') }} 
                            @else 
                                Unknown 
                            @endif
                        </td>
                        <td class="last-session-date">
                            @if (isset($mentor->last_session_date)) 
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $mentor->last_session_date)->format('d-m-Y') }} 
                            @else 
                                Unknown 
                            @endif
                        </td>
                        <td class="days-since-last-session">
                            @if (isset($mentor->days_since_last_session)) 
                                {{ $mentor->days_since_last_session}} 
                            @else 
                                Unknown 
                            @endif
                        </td>
                        <td class="next-planned-session-date">
                            @if (isset($mentor->next_planned_session_date)) 
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $mentor->next_planned_session_date)->format('d-m-Y') }} 
                            @else 
                                Unknown 
                            @endif
                        </td>
                        <td class="expected-session-count">
                            @if (isset($mentor->expected_session_count)) {{ $mentor->expected_session_count }} 
                            @else Unknown 
                            @endif
                        </td>
                        <td class="actual-session-count">{{ $mentor->session_count }}</td>
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