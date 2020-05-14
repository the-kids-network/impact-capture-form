import _ from 'lodash'
import fileIconFor from "./fileicons";
import Tagger from "./tagger";
import DocumentSearch from './search'
import Popper from 'vue-popperjs';
import 'vue-popperjs/dist/vue-popper.css';

const Component = {

    props: {
        usertype: {     
        }
    },

    components: {
        'document-search': DocumentSearch,
        'popper': Popper
    },

    template: `
        <div class="documents list">
            <document-search 
                class="list-search"
                @results="setDocumentFilter($event)"
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
                    <tr 
                        v-for="(document) in _documents"
                        :id="'item-' + document.id"
                        class="item" >   

                        <td class="preview">
                            <popper
                                :trigger="popover.trigger"
                                :options="popover.options">
                                <div class="popper">File type: {{ document.extension }}</div>
                            
                                <span slot="reference">
                                    <span class="hidden">{{ document.extension }}</span>
                                    <span :class="'file-icon far fa-2x ' + fileIconFor(document.extension)" />
                                </span>
                            </popper>
                        </td>
                        <td class="title">
                            {{ document.title }}
                        </td>
                        <td class="actions">
                            <div v-if="document.wip"
                                 class="spinner" />
                            <div v-else
                                 class="actions-row">
                                 <popper
                                    :trigger="popover.trigger"
                                    :options="popover.options">
                                    <div class="popper">Download</div>
                                    <a  slot="reference"
                                        :id="'download-' + document.id"
                                        class="action download" 
                                        @click="handleDownloadDocument(document)">
                                        <span class="glyphicon glyphicon-download"></span>
                                    </a>
                                </popper>
                                <popper
                                    v-if="isAdminUser && document.trashed"
                                    :trigger="popover.trigger"
                                    :options="popover.options">
                                    <div class="popper">Restore</div>
                                    <a  slot="reference"
                                        :id="'restore-' + document.id"
                                        class="action restore"
                                        @click="handleRestoreDocument(document)">
                                        <span class="glyphicon glyphicon-backward"></span>
                                    </a>
                                </popper>
                                <popper
                                    v-if="isAdminUser && document.trashed" 
                                    :trigger="popover.trigger"
                                    :options="popover.options">
                                    <div class="popper">Permenantly Delete</div>
                                    <a  slot="reference"
                                        :id="'delete-' + document.id"
                                        class="action delete"
                                        @click="handleDeleteDocument(document, true)">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </a>
                                </popper>
                                <popper
                                    v-if="isAdminUser && !document.trashed"
                                    :trigger="popover.trigger"
                                    :options="popover.options">
                                    <div class="popper">Trash</div>
                                    <a  slot="reference"
                                        :id="'trash-' + document.id"
                                        class="action trash"
                                        @click="handleDeleteDocument(document, false)">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </popper>
                                <popper
                                    v-if="!document.is_shared"
                                    :trigger="popover.trigger"
                                    :options="popover.options">
                                    <div class="popper">Share</div>
                                    <a  slot="reference"
                                        :id="'share-' + document.id"
                                        class="action share" 
                                        @click="handleShareDocument(document, true)">
                                        <span class="glyphicon glyphicon-share-alt"></span>
                                    </a>
                                </popper>
                                <popper
                                    v-else
                                    :trigger="popover.trigger"
                                    :options="popover.options">
                                    <div class="popper">Unshare</div>
                                    <a  slot="reference"
                                        :id="'unshare-' + document.id"
                                        class="action unshare"  
                                        @click="handleShareDocument(document, false)">
                                        <span class="glyphicon glyphicon-share-alt icon-flipped"></span>
                                    </a>
                                </popper>
                                <popper
                                    v-if="isAdminUser"
                                    :trigger="popover.trigger"
                                    :options="popover.options">
                                    <div class="popper">Tag</div>
                                    <a  slot="reference"
                                        :id="'tag-' + document.id"
                                        class="action tag" 
                                        @click="handleOpenTagger(document.id)">
                                        <span class="glyphicon glyphicon-tags"></span>
                                    </a>
                                </popper>
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
            popover: {
                trigger: 'hover',
                options: {
                    placement: 'top',
                    modifiers: { offset: { offset: '0,10px' } }
                }
            },
            documentIdsToFilter: undefined,
            documents: []
        }
    },

    computed: {
        _documents() {
            return (!this.documentIdsToFilter) ? this.documents : filterDocumentsToIds(this.documents, this.documentIdsToFilter)
        },
        isAdminUser() {
            return this.usertype === 'manager' || this.usertype === 'admin';
        },
    },

    async created() {
        this.setDocuments()
    },

    mounted() {
        
    },

    methods: { 
        removeDocumentFilter() {
            this.documentIdsToFilter = undefined
        },

        setDocumentFilter(documentIdsToFilter) {
            this.documentIdsToFilter = documentIdsToFilter;
        },

        async setDocuments() {
            try {
                this.documents = await this.getDocuments()
            } catch (err) {
                this.$emit('error', "Unable to fetch documents list")
            }
        },

        async handleDownloadDocument(document) {
            try {
                const url = await this.getDocumentDownloadUrl(document.id)
                window.open(url)
            } catch (err) {
                this.$emit('error', "Download unsuccessful")
            }
        },

        handleDeleteDocument(document, hardDelete=false) {
            this.locking(document, async doc => {
                try {
                    const updatedDoc = await this.deleteDocument(doc.id, hardDelete)

                    hardDelete
                        ? this.documents = deleteFromDocuments(this.documents, document)
                        : this.documents = updateDocuments(this.documents, updatedDoc)

                    this.$emit('success', 
                                (hardDelete) ? "Permanent delete successful" : "Trash successful")
                } catch (err) {
                    this.$emit('error', (hardDelete) ? "Permanent delete unsuccessful" : "Trash unsuccessful")
                }
            })
        },

        handleRestoreDocument(document) {
            this.locking(document, async doc => {
                try {
                    const updatedDoc = await this.restoreDocument(doc.id)
                    this.documents = updateDocuments(this.documents, updatedDoc)  
                    this.$emit('success', "Restore successful")
                } catch (err) {
                    this.$emit('error', "Restore unsuccessful")
                }
            })
        },

        async handleShareDocument(document, share=true) {
            this.locking(document, async doc => {
                try {
                    const updatedDoc = await this.shareDocument(doc.id, share)
                    this.documents = updateDocuments(this.documents, updatedDoc)
                    this.$emit('success', (share) ? "Share successful" : "Unshare successful")
                } catch (err) {
                    this.$emit('error', (share) ? "Share not successful" : "Unshare not successful")
                }
            })
        },

        handleOpenTagger(documentId) {
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
        },

        /*
        * Functions that do not interact with component state directly
        */

        async getDocuments() {
            const urlGetDocuments = `/documents`
            return (await axios.get(urlGetDocuments)).data
        },

        async getDocumentDownloadUrl(documentId) {
            const urlGetDownloadUrl = `/documents/${documentId}/download`
            const downloadData = (await axios.get(urlGetDownloadUrl)).data
            return downloadData.download_url
        },

        async deleteDocument(documentId, hardDelete=false) {
            const urlDeleteDocument = `/documents/${documentId}`
            const updatedDoc = (await axios.delete(
                urlDeleteDocument, { params: { 'really_delete': hardDelete } })
            ).data
            return updatedDoc
        },
        async restoreDocument(documentId) {
            const urlRestoreDocument = `/documents/${documentId}/restore`
            const updatedDoc = (await axios.post(urlRestoreDocument)).data
            return updatedDoc
        },

        async shareDocument(documentId, share=true) {
            const urlShareDocument = `/documents/${documentId}/share`
            return (await axios.post(urlShareDocument, { 'share': share })).data
        },

        fileIconFor(extension) {
            return fileIconFor(extension);
        },

        async locking(document, func) {
            this.$set(document, 'wip', true)
            try {
                await func(document);
            } finally {
                this.$set(document, 'wip', false)
            }
        }
    }
};

export default Component;

const filterDocumentsToIds = (documents, idsToFilter) => documents.filter(doc => idsToFilter.includes(doc.id)) 

const updateDocuments = (documents, updatedDocument) => documents.map(d => (d.id === updatedDocument.id) ? updatedDocument: d)

const deleteFromDocuments = (documents, toDelete) => documents.filter(d => d.id !== toDelete.id)