@extends('spark::layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Promote to Finance</div>
                    <div class="panel-body">

                        @include('spark::shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/roles/finance">
                        {{ csrf_field() }}

                            <!-- User's Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">User Name</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="user_id">
                                        @foreach($users as $user)
                                            @if( $user->isMentorOnly() )
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <p class="help-block">Only users that do not have a role can be promoted to Finance</p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Promote to Finance
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
                    <div class="panel-heading">Finances</div>
                    <ul class="list-group">
                        @foreach($users as $user)
                            @if( $user->isFinance() )
                                <li class="list-group-item">{{$user->name}}
                                    <span class="pull-right">
                                        <form action="/roles/finance" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <input type="hidden" name="finance_id" value="{{$user->id}}">
                                            <input type="submit" value="Demote" class="btn btn-xs btn-danger-outline">
                                        </form>
                                    </span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection