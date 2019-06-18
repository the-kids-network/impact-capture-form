@extends('layout.app')

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
    <div class="container">
        <div class="row">
            <div></div>
            <div class="col-md-8 col-md-offset-2">
                <button class="btn btn-lg btn-default" onclick="toggle('.trashed')">
                    Toggle Deactivated Mentors
                </button>
                <hr>
            </div>
        </div>
    </div>

    @include('roles.include.mentor_mentee_pairing_form', ['mentors' => $assignableMentors, 'mentees' => $assignableMentees])

    <div class="container">
        <div class="row">
            <div></div>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Mentors<span class="pull-right"><a class="expand-all">Toggle All</a></span></div>
                    <div class="mentor-table">
                        @foreach($allMentors as $mentor)
                        <div class="mentor-table-row @if($mentor->trashed()) trashed @else not-trashed @endif" style="@if($mentor->trashed()) display: none @endif"> 
                            <span class="mentor name-row list-group-item-info">{{$mentor->name}}</span>
                            <span class="mentor delete-row list-group-item-info">
                                @if($mentor->trashed())
                                    <form style="display: inline-block" action="{{ url('/user/'.$mentor->id.'/restore') }}" id="restore-{{$mentor->id}}" method="post">
                                        {{ csrf_field() }}
                                        <a href="javascript:{}" onclick="document.getElementById('restore-{{$mentor->id}}').submit(); return false;">Restore</a>
                                    </form>
                                    |
                                    <form style="display: inline-block" action="{{ url('/user/'.$mentor->id) }}" id="deactivate-{{$mentor->id}}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input type="hidden" name="really_delete" value="1">
                                        <a href="javascript:{}" onclick="document.getElementById('deactivate-{{$mentor->id}}').submit(); return false;">Delete</a>
                                    </form>
                                @else
                                    <form style="display: inline-block" action="{{ url('/user/'.$mentor->id) }}" id="deactivate-{{$mentor->id}}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input type="hidden" name="really_delete" value="0">
                                        <a href="javascript:{}" onclick="document.getElementById('deactivate-{{$mentor->id}}').submit(); return false;">Deactivate</a>
                                    </form>
                                @endif
                            </span>

                            @if(!$mentor->mentees->isEmpty())
                                <div class="mentor-expand list-group-item-info toggle-btn"
                                            data-target="#mentee_details{{$mentor->id}}" data-toggle="collapse">
                                    <i class="fa fa-bars"></i>
                                </div>
                            @else
                                <div class="list-group-item-info spacer"></div>
                            @endif
                            @if(!$mentor->mentees->isEmpty())
                                <div class="collapse mentee_details" id="mentee_details{{$mentor->id}}">
                                    <div class="mentees">
                                    <h3 class="title">Assigned Mentees</h3>
                                    @forelse($mentor->mentees as $mentee)
                                        <div class="mentee name-row">{{ $mentee->name }}</div>
                                        <span class="mentee delete-row">
                                            <form action="/roles/mentor/{{ $mentor->id }}/mentee/{{ $mentee->id }}" method="post">
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
                        </div> 
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body-scripts')
    <script>
        function toggle(selector){
            $(selector).toggle();
        }
    </script>
@endsection