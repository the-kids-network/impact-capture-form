@extends('layout.app')

@section('content')
<div class="container password-reset">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Reset Password</div>

                <div class="card-body">
                    <form class="form-horizontal" novalidate="true" role="form" method="POST" action="{{ url('/password/reset') }}">
                        {!! csrf_field() !!}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- E-Mail Address -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="emailInput">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="emailInput" type="email" 
                                       class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                                       name="email" value="{{ (old('email')) ? old('email') : $email }}" autofocus>
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="passwordInput">Password</label>

                            <div class="col-md-6">
                                <input id="passwordInput" type="password" 
                                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                                       name="password">
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            </div>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="passwordConfirmInput">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="passwordConfirmInput" type="password" 
                                       class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"  
                                       name="password_confirmation">
                                <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                            </div>
                        </div>

                        <!-- Reset Button -->
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <span class="fa fa-btn fa-refresh"></span> Reset Password
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
