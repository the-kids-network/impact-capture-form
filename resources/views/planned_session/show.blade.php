@extends('layout.app')

@section('content')
    <div class="container planned-session show">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Planned Session Details</div>

                    <div class="card-body">
                        <a type="button" href="/calendar">Go back to calendar</a>

                        @include('shared.errors')

                        <form id="save-planned-session" class="form-horizontal" role="form" method="POST" action="/planned-session/{{ $plannedSession->id }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT"/>
                            <input type="hidden" name="mentee_id" value="{{ $plannedSession->mentee->id }}"/>

                            <!-- Mentee Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="menteeInput">Mentee</label>
                                <div class="col-md-6">
                                    <input id="menteeInput" type="text" class="form-control" name="mentee_name" value="{{ $plannedSession->mentee->name }}" readonly="true">
                                </div>
                            </div>

                            <!-- Date of Session -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="sessionDateInput"
                                       data-toggle="popover" data-trigger="hover" data-content="<?php echo 'Email reminders will be sent if a report has not been saved within three days of a planned session.'; ?>">
                                    Session Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input id="sessionDateInput" type="text" class="form-control datepicker" name="next_session_date" value="{{ old('next_session_date', $plannedSession->date->format('d-m-Y')) }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Location of Session -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="sessionLocation">Session Location</label>
                                <div class="col-md-6">
                                    <input id="sessionLocation" type="text" class="form-control" name="next_session_location" value="{{ old('next_session_location', $plannedSession->location) }}">
                                </div>
                            </div>
                        </form>

                        <form id="delete-planned-session" class="form-horizontal" role="form" method="POST" action="/planned-session/{{ $plannedSession->id }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE"/>
                        </form>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <input type="submit" form="save-planned-session" class="btn btn-xs btn-primary" value="Save"/>
                                <input type="submit" form="delete-planned-session" class="btn btn-xs btn-danger" value="Delete"/>
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