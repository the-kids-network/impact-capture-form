@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Mentor Leave</div>

                    <div class="panel-body">
                        @include('shared.errors')

                        @if(Auth::user()->isMentor()) 
                        <div>This is to book leave for you. If you want to book leave for your mentee, <a href="/mentee/leave/new">use this page instead.</a></div>
                        <br/>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="/mentor/leave">
                        {{ csrf_field() }}

                            @include('mentor_leave.info_message')

                            <!-- Mentor -->
                            @if(Auth::user()->isManager() || Auth::user()->isAdmin()) 
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Mentor</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="mentor_id">
                                            @foreach($mentors as $mentor)
                                                <option value="{{ $mentor->id }}" @if( old('mentor_id') == $mentor->id) selected="selected" @endif>
                                                    {{ $mentor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="mentor_id" value="{{ Auth::user()->id }}"/>
                            @endif

                            <!-- Start date -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover" data-content="<?php echo 'The first day of your leave/holiday.'; ?>">
                                    Start Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="start_date" value="{{ old('start_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- End date -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover" data-content="<?php echo 'The last day (inclusive) of your leave/holiday.'; ?>">
                                    End Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="end_date" value="{{ old('end_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Description (Optional)</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="description" value="{{ old('description') }}">
                                </div>
                            </div>

                            <div class="pull-right">
                                <input type="button" class="btn btn-xs btn-default-outline" value="Cancel" onclick="window.location='{{ url("calendar") }}'"/>
                                <input type="submit" class="btn btn-xs btn-primary-outline" value="Save"/>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('scripts')
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
@endsection


@section('body-scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

    <script>
        $( function() {
            $( ".datepicker" ).datepicker({
                format: 'yyyy-mm-dd'
            });
        } );

        $(document).ready(function() {
            $('[data-toggle="popover"]').popover({
              html: true,
              placement: 'auto left'
            });
          });
    </script>

@endsection