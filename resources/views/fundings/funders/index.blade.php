@extends('layout.app')

@section('content')
    <div class="container funder">
        <div class="row">
            <div></div>
            <div class="col-md-12">
                @include('shared.errors')
            </div>
        </div>
        <div class="row">
            <div></div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Funders</div>
                    <div class="card-body">
                        <form class="form-horizontal" role="form" method="GET" action="/funders">
                            {{ csrf_field() }}
                            @if(Request()->deactivated == "true") 
                            <input name="deactivated" type="hidden" value="false">
                            @else
                            <input name="deactivated" type="hidden" value="true">
                            @endif
                            <button class="btn btn-light" type="submit">
                                Toggle Deactivated Funders
                            </button>
                        </form>

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
                                        <form style="display: inline-block" action="{{ url('/funders/'.$funder->id.'/restore') }}" id="restore-{{$funder->id}}" method="post">
                                            {{ csrf_field() }}
                                            <a href="javascript:{}" onclick="document.getElementById('restore-{{$funder->id}}').submit(); return false;">Restore</a>
                                        </form>
                                        |
                                        <form style="display: inline-block" action="{{ url('/funders/'.$funder->id) }}" id="delete-{{$funder->id}}" class="delete-funder" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="really_delete" value="1">
                                            <a href="javascript:{}" onclick="document.getElementById('delete-{{$funder->id}}').submit(); return false;">Delete</a>
                                        </form>
                                    @else
                                        <form style="display: inline-block" action="{{ url('/funders/'.$funder->id) }}" id="deactivate-{{$funder->id}}" method="post">
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Add Funder</div>
                    <div class="card-body">
                        <form class="form-horizontal" role="form" method="POST" action="/funders">
                            {{ csrf_field() }}
                            <!-- Funder code -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="codeInput">Code</label>

                                <div class="col-md-6">
                                    <input id="codeInput" type="text" class="form-control" name="code" value="{{ old('code') }}" autofocus>
                                </div>
                            </div>
                            <!-- Funder description -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="descInput">Description</label>

                                <div class="col-md-6">
                                    <input id="descInput" type="text" class="form-control" name="description" value="{{ old('description') }}" autofocus>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-sign-in"></i>Add Funder
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