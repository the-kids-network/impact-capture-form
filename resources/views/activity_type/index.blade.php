@extends('layout.app')

@section('content')
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Activity Types</div>
                        <ul class="list-group">
                            @foreach($activity_types as $activity_type)
                            <li class="list-group-item">
                                {{ $activity_type->name }}

                                <div class="pull-right">
                                    @if($activity_type->trashed())
                                        <form action="{{ url('/activity-type/restore/'.$activity_type->id) }}" id="restore-{{$activity_type->id}}" method="post">
                                            {{ csrf_field() }}
                                            <a href="javascript:{}" onclick="document.getElementById('restore-{{$activity_type->id}}').submit(); return false;">Restore</a>
                                        </form>
                                    @else
                                        <form action="{{ url('/activity-type/'.$activity_type->id) }}" id="deactivate-{{$activity_type->id}}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            {{--<input type="submit" value="Deactivate">--}}
                                            <a href="javascript:{}" onclick="document.getElementById('deactivate-{{$activity_type->id}}').submit(); return false;">Deactivate</a>
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
                        <div class="panel-heading">Add Activity Type</div>

                        <div class="panel-body">
                            @include('shared.errors')

                            <form class="form-horizontal" role="form" method="POST" action="/activity-type">
                            {{ csrf_field() }}

                                <!-- Mentee's Name -->
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Activity Type</label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa m-r-xs fa-sign-in"></i>Add Activity Type
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