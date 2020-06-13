<update-contact-information inline-template>
    <div class="card contact-info">
        <div class="card-header">Contact Information</div>

        <div class="card-body">
            <!-- Success Message -->
            <div class="alert alert-success" v-show="form.statusMessage">
                @{{ form.statusMessage }}
            </div>

            <form class="form-horizontal" role="form">
                <!-- Name -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="nameInput">Name</label>

                    <div class="col-md-6">
                        <input id="nameInput" type="text" :class="{'form-control': true, 'is-invalid': form.errors.has('name')}" 
                               name="name" v-model="form.name">
                        <div class="invalid-feedback" v-show="form.errors.has('name')">@{{ form.errors.get('name') }}</div>
                    </div>
                </div>

                <!-- E-Mail Address -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label"  for="emailInput">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="emailInput" type="email" :class="{'form-control': true, 'is-invalid': form.errors.has('email')}" 
                               name="email" v-model="form.email">
                        <div class="invalid-feedback" v-show="form.errors.has('email')">@{{ form.errors.get('email') }}</div>
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
</update-contact-information>
