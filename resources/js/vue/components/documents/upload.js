// For PHP backend, be sure the php.ini is updated to set the post_max_size, max_file_uploads, and upload_max_filesize

import _ from 'lodash'
import { List } from 'immutable'
import filesize from 'filesize'

import { createNamespacedHelpers } from 'vuex'
const { mapState, mapActions, mapMutations } = createNamespacedHelpers('documents/upload')

import statusMixin from '../status-box/mixin'

const STATUS_EDITABLE = 0, STATUS_SAVING = 1
const MAX_FILES_UPLOAD = 10
const LARGE_FILE_SIZE_LIMIT = 314572800; //300MB

const Component = {

    props: {
    },

    mixins: [statusMixin],

    template: `
        <div>
            <status-box 
                ref="status-box"
                class="status" 
                :successes="successes"
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses" />

            <form novalidate>
                <div class="dropbox">
                    <input class="input-file"
                        type="file" multiple 
                        :disabled="isSaving || documentLimitReached" 
                        @change="handleAddFiles($event.target.files)" >

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
                <div class="uploads-container" v-if="documentCount > 0">
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
                            <tr v-for="(document, key) in documentFiles">
                                <td class="remove">
                                    <a href="#" @click.prevent="handleRemoveFile(key)" v-if="!isSaving">
                                        <span class="fa fa-trash-alt"></span>
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
                        <span v-on:click="handleUpload()" class="upload btn btn-success" :disabled="isSaving">
                        <span class="glyphicon glyphicon-upload" /> Upload</span>
                    </div>
                </div>
            </form>
        </div>
    `,

    data() {
        return {
            currentStatus: null,
        }
    },

    computed: {
        ...mapState({
            documentFiles: 'files'
        }),
        documentCount() {
            return this.documentFiles.size;
        },
        documentLimitReached() {
            return this.documentFiles.size >= MAX_FILES_UPLOAD;
        },
        isSaving() {
          return this.currentStatus === STATUS_SAVING;
        },
        maxFilesLimit() {
            return MAX_FILES_UPLOAD
        }
    },
    
    mounted() {
        window.addEventListener('beforeunload', this.beforeUnload , false)
    },

    methods: {
        beforeUnload (e) {
            if (this.documentFiles && this.documentFiles.size != 0) {
                e.returnValue = 'You may have unfinished changes!';
            }
        },

        makeEditable() {
            this.currentStatus = STATUS_EDITABLE;
        },

        isFileLarge(file) {
            return file.size > LARGE_FILE_SIZE_LIMIT;
        },
        
        handleAddFiles(files) {
            List(files).forEach(f => {
                const file = {
                    key: f.name,
                    shared: true,
                    title: f.name,
                    file: f,
                    fileSizeFormatted: filesize(f.size)
                }

                if (!this.documentLimitReached) this.addFile(file) 
            })
        },

        handleRemoveFile(index) {
            this.removeFile(index)  
        },

        async handleUpload() {
            this.clearStatus();
            this.currentStatus = STATUS_SAVING;

            try {
                await this.try("upload file(s)", async () => await this.upload(), {handleSuccess: true})
            } finally {
                this.makeEditable(); 
            }
        },

        ...mapMutations(['addFile', 'removeFile']),

        ...mapActions(['upload'])
    },
};


export default Component;
