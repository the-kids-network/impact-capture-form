@extends('layout.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <button class="btn btn-lg btn-default" onclick="toggle('.trashed')">
                    Toggle Deactivated Mentees
                </button>

                <hr>

                <div class="panel panel-default">
                    <div class="panel-heading">Mentees</div>
                    <ul class="list-group">
                        @foreach($allMentees as $mentee)
                        <li class="list-group-item @if($mentee->trashed()) trashed @else not-trashed @endif" style="@if($mentee->trashed()) display: none @endif">
                            {{ $mentee->first_name }} {{ $mentee->last_name }}
                            <div class="pull-right">
                                @if($mentee->trashed())
                                    <form style="display: inline-block" action="{{ url('/mentee/restore/'.$mentee->id) }}" id="restore-{{$mentee->id}}" method="post">
                                        {{ csrf_field() }}
                                        <a href="javascript:{}" onclick="document.getElementById('restore-{{$mentee->id}}').submit(); return false;">Restore</a>
                                    </form>
                                    |

                                    <form style="display: inline-block" action="{{ url('/mentee/'.$mentee->id) }}" id="deactivate-{{$mentee->id}}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input type="hidden" name="really_delete" value="1">
                                        <a href="javascript:{}" onclick="document.getElementById('deactivate-{{$mentee->id}}').submit(); return false;">Delete</a>
                                    </form>
                                @else
                                    <form style="display: inline-block" action="{{ url('/mentee/'.$mentee->id) }}" id="deactivate-{{$mentee->id}}" method="post">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input type="hidden" name="really_delete" value="0">
                                        <a href="javascript:{}" onclick="document.getElementById('deactivate-{{$mentee->id}}').submit(); return false;">Deactivate</a>
                                    </form>
                                @endif

                            </div>
                            <div class="clearfix"></div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Add Mentee</div>

                    <div class="panel-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/mentee">
                        {{ csrf_field() }}

                            <!-- Mentee's First Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee First Name</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" autofocus>
                                </div>
                            </div>

                            <!-- Mentee's Last Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentee Last Name</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" autofocus>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa m-r-xs fa-sign-in"></i>Add Mentee
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('roles.include.mentor_mentee_pairing_form', ['mentors' => $assignableMentors, 'mentees' => $assignableMentees])

@endsection

@section('body-scripts')
    <script>
        function toggle(selector){
            $(selector).toggle();
        }
    </script>
@endsection