@extends('layout.app')

@section('content')

    <div class="container admin-management">
        <div class="row">
            <div class="col-md-12">
                @include('shared.errors')
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Promote to Admin</div>
                    <div class="card-body">
                        <form id="promoteToAdminForm" class="form-horizontal" role="form" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('put') }}

                            <!-- User's Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label">User Name</label>
                                <div class="col-md-6">
                                    <select id="userSelect" class="form-control" name="user_id">
                                        @foreach($users as $user)
                                            @if($user->isMentor())
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Promote to Admin
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
                <div class="card">
                    <div class="card-header">Admins</div>
                    <ul class="list-group list-group-flush">
                        @foreach($users as $user)
                            @if( $user->isAdmin() )
                                <li class="list-group-item">{{$user->name}}
                                    <span class="float-right">
                                        <form action="/users/{{$user->id}}/roles/admin" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('delete') }}
                                            <input type="hidden" name="admin_id" value="{{$user->id}}">
                                            <input type="submit" value="Demote" class="btn btn-secondary btn-xs btn-danger-outline">
                                        </form>
                                    </span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body-scripts')
    <script type="text/javascript">
        $('#promoteToAdminForm').on('submit', function() {
            const userId = $('#promoteToAdminForm #userSelect').val()
            $('#promoteToAdminForm').attr('action', '/users/'+ userId +'/roles/admin')
        });
    </script>
@endsection