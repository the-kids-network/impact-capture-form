<update-password inline-template>
    <div class="card security">
        <div class="card-header">Update Password</div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-show="form.statusMessage">
                @{{ form.statusMessage }}
            </div>

            <form class="form-horizontal" role="form">
                <!-- Current Password -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="currentPasswordInput">Current Password</label>

                    <div class="col-md-6">
                        <input id="currentPasswordInput" type="password" :class="{'form-control': true, 'is-invalid': form.errors.has('current_password')}"
                               name="current_password" v-model="form.current_password">
                        <div class="invalid-feedback" v-show="form.errors.has('current_password')">@{{ form.errors.get('current_password') }}</div>
                    </div>
                </div>

                <!-- New Password -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="passwordInput">Password</label>

                    <div class="col-md-6">
                        <input id="passwordInput" type="password" :class="{'form-control': true, 'is-invalid': form.errors.has('password')}"
                               name="password" v-model="form.password">
                        <div class="invalid-feedback" v-show="form.errors.has('password')">@{{ form.errors.get('password') }}</div>
                    </div>
                </div>

                <!-- New Password Confirmation -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="passwordConfInput">Confirm Password</label>

                    <div class="col-md-6">
                        <input id="passwordConfInput" type="password" :class="{'form-control': true, 'is-invalid': form.errors.has('password_confirmation')}"
                               name="password_confirmation" v-model="form.password_confirmation">
                        <div class="invalid-feedback" v-show="form.errors.has('password_confirmation')">@{{ form.errors.get('password_confirmation') }}</div>
                    </div>
                </div>

                <!-- Update Button -->
                <div class="form-group row">
                    <div class="offset-md-4 col-md-6">
                        <button type="submit" class="btn btn-primary"
                                @click.prevent="update"
                                :disabled="form.busy">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</update-password>
