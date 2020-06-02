<update-profile-photo :user="user" inline-template>
    <div class="card profile" v-if="user">
        <div class="card-header">Profile Photo</div>

        <div class="card-body">
            <div class="alert alert-danger" v-if="form.errors.has('photo')">
                @{{ form.errors.get('photo') }}
            </div>

            <form class="form-horizontal" role="form">
                <!-- Current photo -->
                <div class="form-group row profile-photo">
                    <label class="col-md-4 col-form-label">Current photo</label>

                    <div class="col-md-auto">
                        <img :src="user.photo" v-if="user.photo" role="img" class="profile-photo-preview img-thumbnail" />
                    </div>

                    <div class="col-md">
                        <button v-if="user.photoType === 'custom'" class="btn btn-primary remove-photo" :disabled="form.busy" @click="remove">Remove Photo</button>
                    </div>
                </div>

                <!-- Update Button -->
                <div class="form-group row select-profile-photo">
                    <label class="col-md-4 col-form-label" :disabled="form.busy" for="newPhotoInput">Select New Photo</label>

                    <div class="col-md-8">
                        <input id="newPhotoInput" ref="photo" type="file" class="form-control" name="photo" @change="update">
                    </div>
                </div>
            </form>
        </div>
    </div>
</update-profile-photo>
