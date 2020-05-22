@extends('layout.app')

@section('content')
        <div class="container emotional-state">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Emotional States</div>
                        <ul class="list-group list-group-flush">
                            @foreach($emotional_states as $emotional_state)
                            <li class="list-group-item ">
                                {{ $emotional_state->name }}

                                <div class="float-right">
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
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Add Emotional State</div>
                        <div class="card-body">
                            @include('shared.errors')

                            <form class="form-horizontal" role="form" method="POST" action="/emotional-state">
                                {{ csrf_field() }}
                                <!-- Emotional_state's Name -->
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus>
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-primary">
                                            Add Emotional State
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection