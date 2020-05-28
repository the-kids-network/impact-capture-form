@extends('layout.app')

<!-- Main Content -->
@section('content')
<div class="container password-reset-request">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Reset Password</div>

                <div class="card-body ">
                    <form class="form-horizontal" novalidate="true" role="form" method="POST" action="{{ url('/password/email') }}">
                        {!! csrf_field() !!}

                        <!-- E-Mail Address -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="emailInput">E-Mail Address</label>

                            <div class="col-md-8">
                                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       id="emailInput" type="email" name="email" value="{{ old('email') }}" autofocus>
                                <div class="invalid-feedback invalid-email">{{ $errors->first('email') }}</div>    
                            </div>
                        </div>

                        <!-- Send Password Reset Link Button -->
                        <div class="form-group row">
                            <div class="col-md-8 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <span class="fa fa-btn fa-envelope"></span> Send Password Reset Link
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
