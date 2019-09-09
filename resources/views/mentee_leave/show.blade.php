@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Mentee Leave</div>

                    <div class="panel-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/mentee/leave">
                        {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{ $menteeLeave->id }}"/>
                            <input type="hidden" name="mentee_id" value="{{ $menteeLeave->mentee->id }}"/>

                            <!-- Start date -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover" data-content="<?php echo 'The first day of your mentee\'s leave/holiday.'; ?>">
                                    Start Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="start_date" value="{{ old('start_date', $menteeLeave->start_date->format('d-m-Y')) }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- End date -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover" data-content="<?php echo 'The last day (inclusive) of your mentee\'s leave/holiday.'; ?>">
                                    End Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="end_date" value="{{ old('end_date',  $menteeLeave->end_date->format('d-m-Y')) }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Description (Optional)</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="description" value="{{ old('description',  $menteeLeave->description) }}">
                                </div>
                            </div>

                            <div class="pull-right">
                                <input type="button" class="btn btn-xs btn-default-outline" value="Cancel" onclick="window.location='{{ url("calendar") }}'"/>
                                <input type="submit" class="btn btn-xs btn-primary-outline" value="Save"/>
                            </div>
                        </form>

                        <form class="form-horizontal" role="form" method="POST" action="/mentee/leave/{{ $menteeLeave->id }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="_method" value="DELETE"/>
                            <input type="submit" class="btn btn-xs btn-danger-outline" value="Delete"/>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body-scripts')
    <script>
        $(function() {
            $( ".datepicker" ).datepicker({
                dateFormat: 'dd-mm-yy'
            });
        });

        $(document).ready(function() {
            $('[data-toggle="popover"]').popover({
              html: true,
              placement: 'auto left'
            });
        });
    </script>

@endsection