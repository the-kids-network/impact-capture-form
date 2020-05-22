@extends('layout.app')

@section('content')

    <div class="container manager-management">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Promote to Manager</div>
                    <div class="card-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/roles/manager">
                            {{ csrf_field() }}
                            <!-- User's Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">User Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="user_id">
                                        @foreach($users as $user)
                                            @if( $user->isMentor() ))
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Assign Manager to Mentor
                    </div>
                    <div class="card-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/roles/assign-manager">
                            {{ csrf_field() }}
                            <!-- Manager's Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Manager Name</label>
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
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">Mentor Name</label>
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
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
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
 
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Managers</div>
                    <div class="card-body">
                        <div class="tkn-list-group container managers-list">
                            @foreach($users as $user)
                                @if( $user->isManager() )
                                <div class="tkn-list-group-item row manager">
                                    <div class="col-md-6">{{$user->name}}</div>
                                    <div class="col-md-auto ml-auto">
                                        <form action="/roles/manager" method="post" >
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <input type="hidden" name="manager_id" value="{{$user->id}}">
                                            <input type="submit" value="Demote" class="btn btn-xs btn-link">
                                        </form>
                                    </div>
                                </div>

                                @foreach($user->assignedMentors as $mentor)
                                <div class="tkn-list-group-item row mentor">
                                    <span class="col-md-12">Manages: {{ $mentor->name }}</span>
                                </div>
                                @endforeach
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection