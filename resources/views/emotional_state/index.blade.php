@extends('spark::layouts.app')

@section('content')
    {{--<home :user="user" inline-template>--}}

        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Emotional States</div>
                        <ul class="list-group">
                            @foreach($emotional_states as $emotional_state)
                            <li class="list-group-item">
                                {{ $emotional_state->name }}
                                <div class="pull-right">
                                    @if($emotional_state->trashed())
                                        <form action="{{ url('/emotional-state/restore/'.$emotional_state->id) }}" id="restore-{{$emotional_state->id}}" method="post">
                                            {{ csrf_field() }}
                                            <a href="javascript:{}" onclick="document.getElementById('restore-{{$emotional_state->id}}').submit(); return false;">Restore</a>
                                        </form>
                                    @else
                                        <form action="{{ url('/emotional-state/'.$emotional_state->id) }}" id="deactivate-{{$emotional_state->id}}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            {{--<input type="submit" value="Deactivate">--}}
                                            <a href="javascript:{}" onclick="document.getElementById('deactivate-{{$emotional_state->id}}').submit(); return false;">Deactivate</a>
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
                        <div class="panel-heading">Add Emotional State</div>

                        <div class="panel-body">
                            @include('spark::shared.errors')

                            <form class="form-horizontal" role="form" method="POST" action="/emotional-state">
                            {{ csrf_field() }}

                                <!-- Emotional_state's Name -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Emotional State</label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa m-r-xs fa-sign-in"></i>Add Emotional State
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{--</home>--}}
@endsection