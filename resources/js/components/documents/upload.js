// For PHP backend, be sure the php.ini is updated to set the post_max_size, max_file_uploads, and upload_max_filesize

import filesize from 'filesize'
import _ from 'lodash'

const STATUS_EDITABLE = 0, STATUS_SAVING = 1
const MAX_FILES_UPLOAD = 10
const LARGE_FILE_SIZE_LIMIT = 314572800; //300MB

const Component = {

    props: [],

    template: `
        <form class="documents upload" novalidate>
            
            <!-- Status messages -->
            <div class="alert alert-success" v-show="hasSuccessMessage">
                {{ uploadSuccess }}
            </div>
            <div class="alert alert-danger" v-if="hasErrorMessages">
                <p v-for="error in uploadErrors">{{ error }}</p>
            </div>

            <div class="dropbox">
                <input class="input-file"
                       type="file" multiple 
                       :disabled="isSaving || documentLimitReached" 
                       @change="addFiles($event.target.files)" >

                <p v-if="documentLimitReached && !isSaving">
                    Limit of {{ maxFilesLimit }} files reached.
                </p>
                <p v-if="!documentLimitReached && !isSaving">
                    Drag your file(s) here to begin<br> or click to browse.
                </p>
                <p v-if="isSaving">
                    Uploading {{ documentCount }} files. <br/><br/>Please be patient as large files take a few minutes.
                </p>
            </div>

            <br />

            <div class="uploads container" v-if="documentCount > 0">
                <table class="uploads table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Size</th>
                            <th>Filename</th>
                            <th>Title</th>
                            <th>Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(document, key) in documentsToUpload">
                            <td class="remove">
                                <a href="#" @click.prevent="removeFile(key)" v-if="!isSaving">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                            </td>
                            <td v-if="isFileLarge(document.file)"
                                class="file-size" >
                                <span class="large-file">{{ document.fileSizeFormatted }}</span>
                            </td>
                            <td v-else
                                class="file-size" >
                                <span>{{ document.fileSizeFormatted }}</span>
                            </td>
                            <td class="file-name">
                                <span :title="document.file.name">{{ document.file.name }}</span>
                            </td>
                            <td class="title">
                                <input type="text" v-model="document.title" :disabled="isSaving"  />
                            </td>
                            <td class="share">
                                <input type="checkbox" v-model="document.shared" :disabled="isSaving" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    <span v-on:click="upload()" class="upload btn btn-success" :disabled="isSaving">
                    <span class="glyphicon glyphicon-upload" /> Upload</span>
                </div>
            </div>
            
        </form>
    `,

    data() {
        return {
            documentsToUpload: [],
            uploadErrors: [],
            uploadSuccess: null,
            currentStatus: null,
        }
    },

    computed: {
        isSaving() {
          return this.currentStatus === STATUS_SAVING;
        },
        hasErrorMessages() {
            return Array.isArray(this.uploadErrors) && this.uploadErrors.length != 0;
        },
        hasSuccessMessage() {
            return this.uploadSuccess != null;
        },
        maxFilesLimit() {
            return MAX_FILES_UPLOAD
        },
        documentCount() {
            return this.documentsToUpload.length;
        },
        documentLimitReached() {
            return this.documentsToUpload.length >= MAX_FILES_UPLOAD;
        }
    },

    methods: {
        beforeUnload (e) {
            if (this.documentsToUpload && this.documentsToUpload.length != 0) {
                e.returnValue = 'You may have unfinished changes!';
            }
        },

        reset() {
            this.documentsToUpload = [];
            this.makeEditable();
        },

        makeEditable() {
            this.currentStatus = STATUS_EDITABLE;
        },

        clearMessages(){
            this.uploadErrors = [],
            this.uploadSuccess = null
        },

        addFiles(fileList) {
            let containsKey = (documentsToUpload, keyToCheck) => documentsToUpload.filter(function(doc){ return doc.key === keyToCheck }).length > 0;

            for (var file of fileList) {
                if (!containsKey(this.documentsToUpload, file.name) && !this.documentLimitReached) {
                    this.documentsToUpload.push( {
                        key: file.name,
                        shared: true,
                        title: file.name,
                        file: file,
                        fileSizeFormatted: filesize(file.size)
                    })
                }
            }
        },

        removeFile(index) {
            this.documentsToUpload.splice(index, 1)
        },

        isFileLarge(file) {
            return file.size > LARGE_FILE_SIZE_LIMIT;
        },

        upload() {
            this.currentStatus = STATUS_SAVING;
            this.clearMessages();

            // build form
            let formData = new FormData();
            for (var doc of this.documentsToUpload) {    
                formData.append('file['+ doc.key +']', doc.file);
                formData.append('file_attributes['+ doc.key +'][title]', doc.title);
                formData.append('file_attributes['+ doc.key +'][shared]', doc.shared);
            }

            // submit form
            axios.post( '/documents',
                formData, 
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    let successMsg = _.get(response, 'data.status');
                    if (successMsg) {
                        this.uploadSuccess = successMsg;
                    } else {
                        this.uploadSuccess = "Upload was successful. Though, double check the document listings to make sure."
                    }
                    this.reset();
                })
                .catch(error => {
                    let errorCode = _.get(error, 'response.data.code');
                    let errors = _.get(error, 'response.data.errors');

                    // display error messages
                    if (errors) {
                        for (var key in errors) {
                            this.uploadErrors.push(...errors[key]);
                        }
                        this.uploadErrors.push("Please contact support if these problems persist.");
                    } else {
                        this.uploadErrors = [
                            "Unknown problem while uploading files.", 
                            "Please contact support if this problem persists."
                        ];
                    }

                    // remove files sucessfully uploaded, leaving only failed ones
                    if (errorCode && errorCode == 'D-1') {
                        let keysToKeep = Object.keys(errors);
                        this.documentsToUpload = this.documentsToUpload.filter(doc => keysToKeep.includes(doc.key));
                    }

                    // To allow for correction, make editable
                    this.makeEditable();
                });
        }
    },

    mounted() {
        this.reset();
        window.addEventListener('beforeunload', this.beforeUnload , false)
    }
};


export default Component;
