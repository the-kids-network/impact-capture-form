@extends('layout.app')

@section('content')
    <div class="container planned-session new">
        <div class="row">
            <div class="col-md-12">
                <nav class="nav page-nav">
                    <a class="nav-link" type="button" href="/calendar">Go back to calendar</a>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Planned Session Details</div>

                    <div class="card-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/planned-session">
                            {{ csrf_field() }}

                            <!-- Mentee -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="menteeInput">Mentee</label>
                                <div class="col-md-6">
                                    <select id="menteeInput" class="form-control" name="mentee_id">
                                        @foreach($mentees as $mentee)
                                            <option value="{{ $mentee->id }}" @if( old('mentee_id') == $mentee->id) selected="selected" @endif>{{ $mentee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Date of Session -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="sessionDateInput"
                                       data-toggle="popover" data-trigger="hover" data-content="<?php echo 'Email reminders will be sent if a report has not been saved within three days of a planned session.'; ?>">
                                    Session Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input id="sessionDateInput" type="text" class="form-control datepicker" name="next_session_date" value="{{ old('next_session_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Location of Session -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="sessionLocation">Session Location</label>
                                <div class="col-md-6">
                                    <input id="sessionLocation" type="text" class="form-control" name="next_session_location" value="{{ old('next_session_location') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <input type="submit" class="btn btn-xs btn-primary" value="Create"/>
                                </div>
                            </div>
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
              placement: 'left'
            });
        });
    </script>
@endsection