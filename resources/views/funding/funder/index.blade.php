@extends('layout.app')

@section('content')
    <div class="container">
        <div class="row">
            <div></div>
            <div class="col-md-8 col-md-offset-2">
                @include('shared.errors')
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div></div>
            <div class="col-md-8 col-md-offset-2">

                <form class="form-horizontal" role="form" method="GET" action="/funder">
                    {{ csrf_field() }}
                    @if(Request()->deactivated == "true") 
                    <input name="deactivated" type="hidden" value="false">
                    @else
                    <input name="deactivated" type="hidden" value="true">
                    @endif
                    <button class="btn btn-lg btn-default" type="submit">
                        Toggle Deactivated Funders
                    </button>
                </form>

                <hr>

                <div class="panel panel-default">
                    <div class="panel-heading">Funders</div>
                    <div class="panel-body">
                        
                        <table class="table documents" 
                                data-toggle="table" 
                                data-search="true" 
                                data-pagination="true">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Code</th>
                                    <th data-sortable="true">Description</th>
                                    <th data-sortable="false">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($funders as $funder)
                                <tr>
                                    <td class="code">
                                        {{$funder->code}}
                                    </td>
                                    <td class="description">
                                        {{$funder->description}}
                                    </td>
                                    <td class="actions">

                                    @if($funder->trashed())
                                        <form style="display: inline-block" action="{{ url('/funder/'.$funder->id.'/restore') }}" id="restore-{{$funder->id}}" method="post">
                                            {{ csrf_field() }}
                                            <a href="javascript:{}" onclick="document.getElementById('restore-{{$funder->id}}').submit(); return false;">Restore</a>
                                        </form>
                                        |
                                        <form style="display: inline-block" action="{{ url('/funder/'.$funder->id) }}" id="delete-{{$funder->id}}" class="delete-funder" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="really_delete" value="1">
                                            <a href="javascript:{}" onclick="document.getElementById('delete-{{$funder->id}}').submit(); return false;">Delete</a>
                                        </form>
                                    @else
                                        <form style="display: inline-block" action="{{ url('/funder/'.$funder->id) }}" id="deactivate-{{$funder->id}}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="really_delete" value="0">
                                            <a href="javascript:{}" onclick="document.getElementById('deactivate-{{$funder->id}}').submit(); return false;">Deactivate</a>
                                        </form>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Add Funder</div>
                    <div class="panel-body">

                        <form class="form-horizontal" role="form" method="POST" action="/funder">
                        {{ csrf_field() }}

                            <!-- Funder code -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Code</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="code" value="{{ old('code') }}" autofocus>
                                </div>
                            </div>

                            <!-- Funder description -->
                            <div class="form-group">
                                <label class="col-md-4 control-label">Description</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="description" value="{{ old('description') }}" autofocus>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa m-r-xs fa-sign-in"></i>Add Funder
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

@section('body-scripts')
    
@endsection