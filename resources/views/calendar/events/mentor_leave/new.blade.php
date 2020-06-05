@extends('layout.app')

@section('content')
    <div class="container mentor-leave new">
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
                    <div class="card-header">Mentor Leave</div>

                    <div class="card-body">
                        @include('shared.errors')

                        @if(Auth::user()->isMentor()) 
                        <div>This is to book leave for you. If you want to book leave for your mentee, <a href="/mentee/leave/new">use this page instead.</a></div>
                        <br/>
                        @endif

                        <form class="form-horizontal" role="form" method="POST" action="/mentor/leave">
                            {{ csrf_field() }}

                            @include('calendar.events.mentor_leave.info_message')

                            <!-- Mentor -->
                            @if(Auth::user()->isManager() || Auth::user()->isAdmin()) 
                                <div class="form-group row">
                                    <label class="col-md-4 col-form-label" for="mentorInput">Mentor</label>
                                    <div class="col-md-6">
                                        <select id="mentorInput" class="form-control" name="mentor_id">
                                            @foreach($mentors as $mentor)
                                                <option value="{{ $mentor->id }}" @if( old('mentor_id') == $mentor->id) selected="selected" @endif>{{ $mentor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="mentor_id" value="{{ Auth::user()->id }}"/>
                            @endif

                            <!-- Start date -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="startDateInput"
                                       data-toggle="popover" data-trigger="hover" data-content="<?php echo 'The first day of your leave/holiday.'; ?>">
                                    Start Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input id="startDateInput" type="text" class="form-control datepicker" name="start_date" value="{{ old('start_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- End date -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="endDateInput"
                                       data-toggle="popover" data-trigger="hover" data-content="<?php echo 'The last day (inclusive) of your leave/holiday.'; ?>">
                                    End Date <i class="fas fa-info-circle"></i>
                                </label>
                                <div class="col-md-6">
                                    <input id="endDateInput" type="text" class="form-control datepicker" name="end_date" value="{{ old('end_date') }}" autocomplete="off">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="descriptionInput">Description (Optional)</label>
                                <div class="col-md-6">
                                    <input id="descriptionInput" type="text" class="form-control" name="description" value="{{ old('description') }}">
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
            $(".datepicker" ).datepicker({
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