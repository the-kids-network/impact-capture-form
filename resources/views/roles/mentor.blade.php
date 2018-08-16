@extends('spark::layouts.app')

@section('page-js-script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("form.delete").submit(function(event) {
                return confirm("This will permanently delete the user.\nIt cannot be undone, although their reports will remain.");
            });
        });
    </script>
@endsection

@section('content')

    @yield('page-js-script')

    @include('roles.include.mentor_mentee_pairing_form')


    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Mentors</div>
                    <ul class="list-group">
                        @foreach($users as $user)
                            @if( $user->isMentorOnly() )
                                <li class="list-group-item list-group-item-info">
                                    {{$user->name}}
                                    <span class="pull-right">
                                        <form action="/roles/mentor/{$user->id}" method="post" class="btn btn-xs btn-danger-outline delete">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <input type="hidden" name="mentor_id" value="{{$user->id}}">
                                            <input type="submit" value="Delete" class="delete">
                                        </form>
                                    </span>
                                </li>

                                @foreach($user->mentees as $mentee)
                                    <li class="list-group-item">Mentors: {{ $mentee->name }}
                                        <span class="pull-right">
                                            <form action="/roles/mentor/{{ $user->id }}/mentee/{{ $mentee->id }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <input type="hidden" name="mentee_id" value="{{$mentee->id}}">
                                                <input type="submit" value="Disassociate" class="btn btn-xs btn-danger-outline">
                                            </form>
                                        </span>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('page-js-script2')
@endsection