import _ from 'lodash'
import fileIconFor from "./fileicons";
import Tagger from "./tagger";
import DocumentSearch from './search'

const Component = {

    props: {
        usertype: {     
        }
    },

    components: {
        DocumentSearch
    },

    template: `
        <div class="documents list">
            <document-search 
                class="list-search"
                @results="filterDocuments($event)"
                @clear="removeDocumentFilter"
                @error="$emit('error', $event)" />

            <table class="items table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="item" v-for="(document) in _documents">   
                        <td class="preview">
                            <span class="hidden">{{ document.extension }}</span>
                            <span :class="'file-icon far fa-2x ' + fileIconFor(document.extension)"
                                data-toggle="popover" data-trigger="hover" data-placement="top" :data-content="'File type: ' + document.extension">
                            </span>
                        </td>
                        <td class="title">
                            {{ document.title }}
                        </td>
                        <td class="actions">
                            <div>
                                <a 
                                    :id="'download-' + document.id"
                                    class="action" 
                                    @click="downloadDocument(document.id)"
                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Download">
                                    <span class="glyphicon glyphicon-download"></span>
                                </a>
                                <a 
                                    v-if="isAdminUser && document.trashed"    
                                    :id="'restore-' + document.id"
                                    class="action"
                                    @click="restoreDocument(document.id)"
                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Restore">
                                    <span class="glyphicon glyphicon-backward"></span>
                                </a>
                                <a 
                                    v-if="isAdminUser && document.trashed"    
                                    :id="'delete-' + document.id"
                                    class="action"
                                    @click="deleteDocument(document.id, true)"
                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Permenantly Delete">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                                <a 
                                    v-if="isAdminUser && !document.trashed"    
                                    :id="'trash-' + document.id"
                                    class="action"
                                    @click="deleteDocument(document.id, false)"
                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Trash">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                                <a 
                                    v-if="!document.is_shared"
                                    :id="'share-' + document.id"
                                    class="action" 
                                    @click="shareDocument(document.id, true)"
                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Share">
                                    <span class="glyphicon glyphicon-share-alt"></span>
                                </a>
                                <a 
                                    v-else
                                    :id="'unshare-' + document.id"
                                    class="action"  
                                    @click="shareDocument(document.id, false)"
                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Unshare">
                                    <span class="glyphicon glyphicon-share-alt icon-flipped"></span>
                                </a>
                                <a 
                                    v-if="isAdminUser"
                                    :id="'tag-' + document.id"
                                    class="action" 
                                    @click="openDocumentTagger(document.id)"
                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="Tag">
                                    <span class="glyphicon glyphicon-tags"></span>
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <modals-container/>
        </div>
    `,

    data() {
        return {
            documentIdsToFilter: undefined,
            documents: []
        }
    },

    computed: {
        _documents() {
            return (!this.documentIdsToFilter) ? this.documents : filterDocuments(this.documents, this.documentIdsToFilter)
        },
        isAdminUser() {
            return this.usertype === 'manager' || this.usertype === 'admin';
        },
    },

    async created() {
        this.getDocuments()
    },

    mounted() {

    },

    methods: {
        removeDocumentFilter() {
            this.documentIdsToFilter = undefined
        },

        filterDocuments(documentIdsToFilter) {
            this.documentIdsToFilter = documentIdsToFilter;
        },

        async getDocuments() {
            const urlGetDocuments = `/documents`
            try {
                const documents = (await axios.get(urlGetDocuments)).data
                this.documents = documents
            } catch (err) {
                this.$emit('error', "Unable to fetch documents list")
            }
        },

        async downloadDocument(documentId) {
            const urlGetDownloadUrl = `/documents/${documentId}/download`
            try {
                const downloadData = (await axios.get(urlGetDownloadUrl)).data
                window.open(downloadData.download_url)
            } catch (err) {
                this.$emit('error', "Download unsuccessful")
            }
        },

        async deleteDocument(documentId, hardDelete=false) {
            const urlDeleteDocument = `/documents/${documentId}`
            try {
                const updatedDoc = (await axios.delete(
                    urlDeleteDocument, { params: { 'really_delete': hardDelete } })
                ).data

                if (hardDelete) {
                    this.documents = this.documents.filter(d => d.id !== documentId)
                } else {
                    this.documents = this.documents.map(d => (d.id === documentId) ? updatedDoc: d)
                }

                this.$emit('success', 
                            (hardDelete) ? "Permanent delete successful" : "Trash successful")
            } catch (err) {
                this.$emit('error', (hardDelete) ? "Permanent delete unsuccessful" : "Trash unsuccessful")
            }
        },

        async restoreDocument(documentId) {
            const urlRestoreDocument = `/documents/${documentId}/restore`
            try {
                const updatedDoc = (await axios.post(urlRestoreDocument)).data
                this.documents = this.documents.map(d => (d.id === documentId) ? updatedDoc: d)
                this.$emit('success', "Restore successful")
            } catch (err) {
                this.$emit('error', "Restore unsuccessful")
            }
        },

        async shareDocument(documentId, share=true) {
            const urlShareDocument = `/documents/${documentId}/share`
            try {
                const updatedDoc = (await axios.post(urlShareDocument, { 'share': share })).data
                this.documents = this.documents.map(d => (d.id === documentId) ? updatedDoc: d)
                this.$emit('success', (share) ? "Share successful" : "Unshare successful")
            } catch (err) {
                this.$emit('error', (share) ? "Share not successful" : "Unshare not successful")
            }
        },

        fileIconFor(extension) {
            return fileIconFor(extension);
        },

        openDocumentTagger(documentId) {
            this.$modal.show(
                Tagger, 
                { 
                    // props to modal component
                    documentId: documentId    
                }, 
                {
                    // modal parameters
                    classes: ['document-tagger-modal'],
                    adaptive: true,
                    draggable: true,
                    clickToClose: true,
                    height: "auto"
                }, 
                {
                    // modal event listeners
                    'opened': () => {},
                    'closed': () => {}
                }
            )
        }
    }
};

export default Component;

const filterDocuments = (documents, idsToFilter) => documents.filter(doc => idsToFilter.includes(doc.id)) 
