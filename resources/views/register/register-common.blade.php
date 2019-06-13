<!-- Basic Profile -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span>
                    Register
                </span>
            </div>

            <div class="panel-body">
                <!-- Generic Error Message -->
                <div class="alert alert-danger" v-if="registerForm.errors.has('form')">
                    @{{ registerForm.errors.get('form') }}
                </div>

                <!-- Registration Form -->
                @include('register.register-common-form')
            </div>
        </div>
    </div>
</div>
