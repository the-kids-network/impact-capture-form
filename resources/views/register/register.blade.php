@extends('layout.app')

@section('content')
<register inline-template>
    <div class="container registration">
        <!-- Basic Profile -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span>Register</span>
                    </div>

                    <div class="card-body">
                        <!-- Generic Error Message -->
                        <div class="alert alert-danger" v-if="registerForm.errors.has('form')">
                            @{{ registerForm.errors.get('form') }}
                        </div>

                        <!-- Registration Form -->
                        <form class="form-horizontal" role="form">

                            <div class="alert alert-success" v-show="registerForm.statusMessage">
                                @{{ registerForm.statusMessage }}
                            </div>
                        
                            <!-- Name -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="nameInput">Name</label>
                                <div class="col-md-6">
                                    <input id="nameInput" type="text" 
                                           :class="{'form-control': true, 'is-invalid': registerForm.errors.has('name')}" 
                                           name="name" v-model="registerForm.name" autofocus>
                                    <div class="invalid-feedback" v-show="registerForm.errors.has('name')">@{{ registerForm.errors.get('name') }}</div>
                                </div>
                            </div>
                        
                            <!-- E-Mail Address -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="emailInput">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input id="emailInput" type="email" 
                                           :class="{'form-control': true, 'is-invalid': registerForm.errors.has('email')}" 
                                           name="email" v-model="registerForm.email">
                                    <div class="invalid-feedback" v-show="registerForm.errors.has('email')">@{{ registerForm.errors.get('email') }}</div>
                                </div>
                            </div>
                        
                            <!-- Password -->
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label" for="passwordInput">Password</label>
                                <div class="col-md-6">
                                    <input id="passwordInput" type="password" 
                                           :class="{'form-control': true, 'is-invalid': registerForm.errors.has('password')}" 
                                           name="password" v-model="registerForm.password">
                                    <div class="invalid-feedback" v-show="registerForm.errors.has('password')">@{{ registerForm.errors.get('password') }}</div>
                                </div>
                            </div>
                        
                            <!-- Password Confirmation -->
                            <div class="form-group row" >
                                <label class="col-md-4 col-form-label" for="passwordConfirmInput">Confirm Password</label>
                                <div class="col-md-6">
                                    <input id="passwordConfirmInput" type="password" 
                                           :class="{'form-control': true, 'is-invalid': registerForm.errors.has('password_confirmation')}"
                                           name="password_confirmation" v-model="registerForm.password_confirmation">
                                    <div class="invalid-feedback" v-show="registerForm.errors.has('password_confirmation')">@{{ registerForm.errors.get('password_confirmation') }}</div>
                                </div>
                            </div>
                        
                            <!-- Terms And Conditions -->
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <button class="btn btn-primary" @click.prevent="register" :disabled="registerForm.busy">
                                        <span v-if="registerForm.busy">
                                            <i class="fa fa-btn fa-spinner fa-spin"></i>Registering
                                        </span>
                                        <span v-else>
                                            <span class="fa fa-btn fa-check-circle"></span> Register
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>                
                    </div>
                </div>
            </div>
        </div>
    </div>
</register>
@endsection
