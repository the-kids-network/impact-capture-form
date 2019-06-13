@extends('layout.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Promote to Manager</div>
                    <div class="panel-body">

                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/roles/manager">
                        {{ csrf_field() }}

                            <!-- User's Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">User Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="user_id">
                                        @foreach($users as $user)
                                            @if( $user->isMentor() ))
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <p class="help-block">Only users that do not have a role can be promoted to Manager</p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Promote to Manager
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Assign Manager to Mentor
                    </div>
                    <div class="panel-body">

                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/roles/assign-manager">
                        {{ csrf_field() }}

                            <!-- Manager's Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Manager Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="manager_id">
                                        @foreach($users as $user)
                                            @if($user->isManager())
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Mentor's Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Mentor Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="mentor_id">
                                        @foreach($users as $user)
                                            @if( $user->isMentor() )
                                                <option value="{{ $user->id }}">{{ $user->name }}@if($user->manager), Current Manager: {{ $user->manager->name }}@endif </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Assign Manager to Mentor
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Managers</div>
                    <ul class="list-group">
                        @foreach($users as $user)
                            @if( $user->isManager() )
                                <li class="list-group-item list-group-item-info">{{$user->name}}
                                    <span class="pull-right">
                                        <form action="/roles/manager" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <input type="hidden" name="manager_id" value="{{$user->id}}">
                                            <input type="submit" value="Demote" class="btn btn-xs btn-danger-outline">
                                        </form>
                                    </span>
                                </li>

                                @foreach($user->assignedMentors as $mentor)
                                    <li class="list-group-item">Manages: {{ $mentor->name }}</li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection