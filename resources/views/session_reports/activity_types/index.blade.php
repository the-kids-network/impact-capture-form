@extends('layout.app')

@section('content')
        <div class="container activity-type">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Activity Types</div>
                        <ul class="list-group list-group-flush">
                            @foreach($activity_types as $activity_type)
                            <li class="list-group-item">
                                {{ $activity_type->name }}

                                <div class="float-right">
                                    @if($activity_type->trashed())
                                        <form action="{{ url('/activity-types/'.$activity_type->id.'/restore') }}" id="restore-{{$activity_type->id}}" method="post">
                                            {{ csrf_field() }}
                                            <a href="javascript:{}" onclick="document.getElementById('restore-{{$activity_type->id}}').submit(); return false;">Restore</a>
                                        </form>
                                    @else
                                        <form action="{{ url('/activity-types/'.$activity_type->id) }}" id="deactivate-{{$activity_type->id}}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            {{--<input type="submit" value="Deactivate">--}}
                                            <a href="javascript:{}" onclick="document.getElementById('deactivate-{{$activity_type->id}}').submit(); return false;">Deactivate</a>
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
                        <div class="card-header">Add Activity Type</div>

                        <div class="card-body">
                            @include('shared.errors')

                            <form class="form-horizontal" role="form" method="POST" action="/activity-types">
                            {{ csrf_field() }}

                                <!-- Mentee's Name -->
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <input id="mentee-name" type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group row">
                                    <div class="col-md-8">
                                        <button type="submit" class="btn btn-primary">
                                            Add Activity Type
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