@extends('spark::layouts.app')

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("form.delete").submit(function(event) {
                return confirm("This will permanently delete the user.\nIt cannot be undone, although their reports will remain.");
            });

            $(".expand-all").click(function(event) {
                $(".mentor-expand").click();
            });
        });
    </script>

@endsection

@section('content')

    @include('roles.include.mentor_mentee_pairing_form')

    <div class="container">
        <div class="row">
            <div></div>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Mentors<span class="pull-right"><a class="expand-all">Toggle All</a></span></div>
                    <div class="mentor-table">
                        @foreach($users as $user)
                            @if( $user->isMentor() )
                                    <span class="mentor name-row list-group-item-info">{{$user->name}}</span>
                                    <span class="mentor delete-row list-group-item-info">
                                        <form action="/roles/mentor/{$user->id}" method="post" class="delete">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <input type="hidden" name="mentor_id" value="{{$user->id}}">
                                            <input type="submit" value="Delete" class="btn btn-xs btn-danger-outline">
                                        </form>
                                    </span>
                                    @if(!$user->mentees->isEmpty())
                                        <div class="mentor-expand list-group-item-info toggle-btn"
                                                    data-target="#mentee_details{{$user->id}}" data-toggle="collapse">
                                            <i class="fa fa-bars"></i>
                                        </div>
                                    @else
                                        <div class="list-group-item-info spacer"></div>
                                    @endif
                                    @if(!$user->mentees->isEmpty())
                                        <div class="collapse mentee_details" id="mentee_details{{$user->id}}">
                                            <div class="mentees">
                                            <h3 class="title">Assigned Mentees</h3>
                                        @forelse($user->mentees as $mentee)
                                            <div class="mentee name-row">{{ $mentee->name }}</div>
                                            <span class="mentee delete-row">
                                                <form action="/roles/mentor/{{ $user->id }}/mentee/{{ $mentee->id }}" method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <input type="hidden" name="mentee_id" value="{{$mentee->id}}">
                                                    <input type="submit" value="Disassociate" class="btn btn-xs btn-danger-outline">
                                                </form>
                                            </span>
                                            <div class="mentee spacer"></div>
                                        @endforeach
                                            </div>
                                        </div>
                                    @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection