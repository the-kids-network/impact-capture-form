@extends('layout.app')

@section('content')
    <div class="container mentor-management">
        <div class="row">
            <div class="col-md-12">
                @include('roles.include.mentor_mentee_pairing_form', ['mentors' => $assignableMentors, 'mentees' => $assignableMentees])
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Mentors<span class="float-right"><a class="expand-all">Toggle Mentees</a></span></div>
                    <div class="card-body">
                        <div>
                            <button class="btn btn btn-light" onclick="toggle('.deactivated')">
                                Toggle Deactivated Mentors
                            </button>
                        </div>
                        <br/>
                        <div class="tkn-list-group mentors-list">
                            @foreach($allMentors as $mentor)
                                @php
                                    $deactivated = $mentor->trashed()
                                @endphp
                                <div class="mentor-{{$mentor->id}}-container @if($deactivated) deactivated @endif" style="@if($deactivated) display: none @endif">
                                    <div class="container">
                                        <div class="tkn-list-group-item row mentor"> 
                                            <!-- Name -->
                                            <div class="col-10 order-1 col-md-5 order-md-1">
                                                <span>{{$mentor->name}}</span>
                                            </div>
                                            <!-- Actions -->
                                            <div class="col-12 order-3 col-md-6 order-md-3">
                                                <span>
                                                    @if($mentor->trashed())
                                                        <form style="display: inline-block" action="{{ url('/user/'.$mentor->id.'/restore') }}" id="restore-{{$mentor->id}}" method="post">
                                                            {{ csrf_field() }}
                                                            <a href="javascript:{}" class="btn btn-link" onclick="document.getElementById('restore-{{$mentor->id}}').submit(); return false;">Restore</a>
                                                        </form>
                                                        |
                                                        <form style="display: inline-block" action="{{ url('/user/'.$mentor->id) }}" id="delete-{{$mentor->id}}" method="post">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                            <input type="hidden" name="really_delete" value="1">
                                                            <a href="javascript:{}" class="btn btn-link delete-mentor" onclick="deleteMentorConfirm({{$mentor->id}})">Delete</a>
                                                        </form>
                                                    @else
                                                        <form style="display: inline-block" action="{{ url('/user/'.$mentor->id) }}" id="deactivate-{{$mentor->id}}" method="post">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                            <input type="hidden" name="really_delete" value="0">
                                                            <a href="javascript:{}" class="btn btn-link" onclick="document.getElementById('deactivate-{{$mentor->id}}').submit(); return false;">Deactivate</a>
                                                        </form>
                                                    @endif
                                                </span>
                                            </div>
                                            <!-- Mentees expand -->
                                            @if(!$mentor->mentees->isEmpty())
                                            <div class="expander col-2 order-2 col-md-1 order-md-3"
                                                data-target="#mentees_for_mentor_{{$mentor->id}}" data-toggle="collapse">
                                                <span class="fa fa-bars"></span>
                                            </div>
                                            @else
                                            <div class="col-2 order-2 col-md-1 order-md-3 ml-auto"></div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Mentees -->
                                    @if(!$mentor->mentees->isEmpty())
                                    <div class="container collapse mentor_mentees" id="mentees_for_mentor_{{$mentor->id}}">
                                        @foreach($mentor->mentees as $mentee)
                                        <div class="row tkn-list-group-item mentee">       
                                            <div class="col-10 col-md-5 name">{{ $mentee->name }}</div>
                                            <div class="col-12 col-md-6">
                                                <form action="/roles/mentor/{{ $mentor->id }}/mentee/{{ $mentee->id }}" method="post" id="disassociate-mentor{{$mentor->id}}-mentee{{$mentee->id}}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <input type="hidden" name="mentee_id" value="{{$mentee->id}}">
                                                    <a href="javascript:{}" class="btn btn-link" onclick="document.getElementById('disassociate-mentor{{$mentor->id}}-mentee{{$mentee->id}}').submit(); return false;">Disassociate</a>
                                                </form>
                                            </div>
                                            <div class="col-2 col-md-1"></div> 
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body-scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".expand-all").click(function(event) {
                $(".expander").click()
            });
        });
    </script>
    <script>
        function toggle(selector){
            $(selector).slideToggle(300);
        }
    </script>
    <script>
        function deleteMentorConfirm(mentorId) {
            const swal = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-primary btn-mentor-delete-confirm',
                        cancelButton: 'btn btn-danger btn-mentor-delete-cancel'
                    },
                    buttonsStyling: false
                })

                swal.fire(
                {
                    title: 'Are you sure?',
                    text: "This will permanently delete the user.\nIt cannot be undone, although their reports will remain.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }
            ).then((result) => {
                if (result.value) {
                    document.getElementById('delete-' + mentorId).submit();
                }
            })
        }
    </script>
@endsection