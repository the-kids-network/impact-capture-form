<update-profile-photo :user="user" inline-template>
    <div>
        <div class="panel panel-default" v-if="user">
            <div class="panel-heading">Profile Photo</div>

            <div class="panel-body">
                <div class="alert alert-danger" v-if="form.errors.has('photo')">
                    @{{ form.errors.get('photo') }}
                </div>

                <form class="form-horizontal" role="form">
                    <!-- Photo Preview-->
                    <div class="form-group">
                        <label class="col-md-4 control-label">&nbsp;</label>

                        <div class="col-md-6">
                            <img :src="user.photo" v-if="user.photo" role="img" class="profile-photo-preview" />
                        </div>
                    </div>

                    <!-- Update Button -->
                    <div class="form-group">
                        <label class="col-md-4 control-label">&nbsp;</label>

                        <div class="col-md-6">
                            <label type="button" class="btn btn-primary btn-upload" :disabled="form.busy">
                                <span>Select New Photo</span>

                                <input ref="photo" type="file" class="form-control" name="photo" @change="update">
                            </label>
                        </div>
                    </div>

                    <!-- Delete Button -->
                    <div class="form-group" v-if="!user.photo.includes('gravatar')">
                        <label class="col-md-4 control-label">&nbsp;</label>

                        <div class="col-md-6">
                            <label type=button class="btn btn-info" :disabled="form.busy" @click="remove">
                                <span>Remove Photo</span>         
                            </label>               
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</update-profile-photo>
