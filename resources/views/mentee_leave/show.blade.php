@extends('layout.app')

@section('content')
    <div class="container mentee-leave show">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Mentee Leave</div>

                    <div class="card-body">
                        <a type="button" href="/calendar">Go back to calendar</a>

                        @include('shared.errors')

                        <form id="save-leave" class="form-horizontal" role="form" method="POST" action="/mentee/leave/{{ $menteeLeave->id }}">

                            {{ csrf_field() }}

                            <input type="hidden" name="_method" value="PUT"/>
                            <input type="hidden" name="mentee_id" value="{{ $menteeLeave->mentee->id }}"/>

                            <!-- Start date -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="startDateInput"
                                       data-toggle="popover" data-trigger="hover" data-content="<?php echo 'The first day of your mentee\'s leave/holiday.'; ?>">
                                    Start Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input id="startDateInput" type="text" class="form-control datepicker" name="start_date" value="{{ old('start_date', $menteeLeave->start_date->format('d-m-Y')) }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- End date -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="endDateInput"
                                       data-toggle="popover" data-trigger="hover" data-content="<?php echo 'The last day (inclusive) of your mentee\'s leave/holiday.'; ?>">
                                    End Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input id="endDateInput" type="text" class="form-control datepicker" name="end_date" value="{{ old('end_date',  $menteeLeave->end_date->format('d-m-Y')) }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="descriptionInput">Description (Optional)</label>
                                <div class="col-md-6">
                                    <input id="descriptionInput" type="text" class="form-control" name="description" value="{{ old('description',  $menteeLeave->description) }}">
                                </div>
                            </div>
                        </form>

                        <form id="delete-leave" class="form-horizontal" role="form" method="POST" action="/mentee/leave/{{ $menteeLeave->id }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE"/>
                        </form>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <input type="submit" form="save-leave" class="btn btn-xs btn-primary" value="Save"/>
                                <input type="submit" form="delete-leave" class="btn btn-xs btn-danger" value="Delete"/>
                            </div>
                        </div>
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
              placement: 'left'
            });
        });
    </script>
@endsection