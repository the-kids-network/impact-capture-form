@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Planned Session Details</div>

                    <div class="panel-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/planned-session">
                        {{ csrf_field() }}

                            <!-- Mentee -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="mentee_id">
                                        @foreach($mentees as $mentee)
                                            <option value="{{ $mentee->id }}" @if( old('mentee_id') == $mentee->id) selected="selected" @endif>{{ $mentee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Date of Session -->
                            <div class="form-group">
                                <label class="col-md-4 control-label" data-toggle="popover" data-trigger="hover" data-content="<?php echo 'Email reminders will be sent if a report has not been saved within three days of a planned session.'; ?>">
                                    Session Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control datepicker" name="next_session_date" value="{{ old('next_session_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Location of Session -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Session Location</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="next_session_location" value="{{ old('next_session_location') }}">
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