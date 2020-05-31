@extends('layout.app')

@section('content')
    <div class="container mentee-management">
        <div class="row">
            <div class="col-md-12">
                <div class="card mentee-existing">
                    <div class="card-header">Mentees</div>

                    <div class="card-body">
                        <button class="btn btn-light" onclick="toggle('.trashed')">
                            Toggle Deactivated Mentees
                        </button>
                        <br/><br/>
                        <div class="tkn-list-group mentee-list container">
                            @foreach($allMentees as $mentee)
                            <div class="tkn-list-group-item row mentee @if($mentee->trashed()) trashed @else not-trashed @endif" style="@if($mentee->trashed()) display: none @endif">
                                <div class="col-md-4 name">
                                    {{ $mentee->name }}
                                </div>
                                <div class="col-md-auto ml-auto actions">
                                    @if($mentee->trashed())
                                        <form style="display: inline-block" action="{{ url('/mentee/restore/'.$mentee->id) }}" id="restore-{{$mentee->id}}" method="post">
                                            {{ csrf_field() }}
                                            <a class="btn btn-link" href="javascript:{}" onclick="document.getElementById('restore-{{$mentee->id}}').submit(); return false;">Restore</a>
                                        </form>
                                        |
                                        <form style="display: inline-block" action="{{ url('/mentee/'.$mentee->id) }}" id="deactivate-{{$mentee->id}}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="really_delete" value="1">
                                            <a class="btn btn-link" href="javascript:{}" onclick="document.getElementById('deactivate-{{$mentee->id}}').submit(); return false;">Delete</a>
                                        </form>
                                    @else
                                        <form style="display: inline-block" action="{{ url('/mentee/'.$mentee->id) }}" id="deactivate-{{$mentee->id}}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <input type="hidden" name="really_delete" value="0">
                                            <a class="btn btn-link" href="javascript:{}" onclick="document.getElementById('deactivate-{{$mentee->id}}').submit(); return false;">Deactivate</a>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mentee-add">
                    <div class="card-header">Add Mentee</div>

                    <div class="card-body">
                        @include('shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/mentee">
                            {{ csrf_field() }}
                            <!-- Mentee's First Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="firstNameInput">Mentee First Name</label>

                                <div class="col-md-6">
                                    <input id="firstNameInput" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" autofocus>
                                </div>
                            </div>
                            <!-- Mentee's Last Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="lastNameInput">Mentee Last Name</label>

                                <div class="col-md-6">
                                    <input id="lastNameInput" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" autofocus>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Mentee
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
                @include('roles.include.mentor_mentee_pairing_form', ['mentors' => $assignableMentors, 'mentees' => $assignableMentees])
            </div>
        </div>
    </div>
@endsection

@section('body-scripts')
    <script>
        function toggle(selector){
            $(selector).slideToggle(300);
        }
    </script>
@endsection