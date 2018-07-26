@extends('spark::layouts.app')

@section('content')
    {{--<home :user="user" inline-template>--}}

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Physical Appearances</div>
                    <ul class="list-group">
                        @foreach($physical_appearances as $physical_appearance)
                            <li class="list-group-item">
                                {{ $physical_appearance->name }}
                                <div class="pull-right">
                                    @if($physical_appearance->trashed())
                                        <form action="{{ url('/physical-appearance/restore/'.$physical_appearance->id) }}" id="restore-{{$physical_appearance->id}}" method="post">
                                            {{ csrf_field() }}
                                            <a href="javascript:{}" onclick="document.getElementById('restore-{{$physical_appearance->id}}').submit(); return false;">Restore</a>
                                        </form>
                                    @else
                                        <form action="{{ url('/physical-appearance/'.$physical_appearance->id) }}" id="deactivate-{{$physical_appearance->id}}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            {{--<input type="submit" value="Deactivate">--}}
                                            <a href="javascript:{}" onclick="document.getElementById('deactivate-{{$physical_appearance->id}}').submit(); return false;">Deactivate</a>
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
                    <div class="panel-heading">Add Physical Appearance</div>

                    <div class="panel-body">
                        @include('spark::shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/physical-appearance">
                        {{ csrf_field() }}

                        <!-- Physical_appearance's Name -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Physical Appearance</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}" autofocus>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa m-r-xs fa-sign-in"></i>Add Physical Appearance
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