@extends('spark::layouts.app')

@section('content')

    @include('roles.include.mentor_mentee_pairing_form')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Mentors</div>
                    <ul class="list-group">
                        @foreach($users as $user)
                            @if( $user->isMentorOnly() )
                                <li class="list-group-item list-group-item-info">{{$user->name}}</li>

                                @foreach($user->mentees as $mentee)
                                    <li class="list-group-item">Mentors: {{ $mentee->name }}
                                        <span class="pull-right">
                                            <form action="/roles/mentor" method="post">
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