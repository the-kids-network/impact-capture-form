@extends('layout.app')

@section('content')
    <div class="container fundings">
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
                    <div class="card-header">Mentor Fundings</div>
                    <div class="card-body">
                        <table class="table documents" 
                                data-toggle="table" 
                                data-search="true" 
                                data-pagination="true">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Mentor</th>
                                    <th data-sortable="true">Funder</th>
                                    <th data-sortable="true">Year</th>
                                    <th data-sortable="false">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($fundings as $funding)
                                <tr>
                                    <td class="mentor">
                                        {{$funding->mentor->name}}
                                    </td>
                                    <td class="funder">
                                        {{$funding->funder->code}}
                                    </td>
                                    <td class="year">
                                        {{$funding->funding_year}}
                                    </td>
                                    <td class="actions">
                                        <form style="display: inline-block" action="{{ url('/fundings/'.$funding->id) }}" id="delete-{{$funding->id}}" class="delete-funding" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <a href="javascript:{}" onclick="document.getElementById('delete-{{$funding->id}}').submit(); return false;">Delete</a>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('funding.export') }}">Download All Data as CSV</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Create Mentor Funding
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" role="form" method="POST" action="/fundings">
                        {{ csrf_field() }}

                            <!-- Mentor's Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="mentorNameInput">Mentor Name</label>
                                <div class="col-md-6">
                                    <select id="mentorNameInput" class="form-control" name="mentor_id">
                                        @foreach($mentors as $mentor)
                                            <option value="{{ $mentor->id }}" @if( old('mentor_id') == $mentor->id ) selected="selected" @endif>
                                                {{ $mentor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Funder Code -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="funderInput">Funder</label>
                                <div class="col-md-6">
                                    <select id="funderInput" class="form-control" name="funder_id" dusk="funder-list">
                                        @foreach($funders as $funder)
                                            <option value="{{ $funder->id }}" @if( old('funder_id') == $funder->id ) selected="selected" @endif>
                                                {{ $funder->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Funding Year -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="yearInput">Funding Year</label>
                                <div class="col-md-6">
                                    <input id="yearInput" type="text" class="form-control" name="funding_year" value="{{ old('funding_year') }}" autofocus>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Create Funding
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
