import _ from 'lodash'
import Popper from 'vue-popperjs';
import 'vue-popperjs/dist/vue-popper.css';
import { createNamespacedHelpers } from 'vuex'
const { mapActions, mapGetters } = createNamespacedHelpers('documents/search')

import { extractErrors } from '../../utils/api'
import fileIconFor from "./fileicons";
import statusMixin from '../status-box/mixin'
import Paginator from "../pagination/paginator"
import Tagger from "./tagger"

const Component = {

    props: {
    },

    mixins: [statusMixin],

    components: {
        'paginator': Paginator,
        'popper': Popper
    },

    template: `
        <div class="documents-list">
            <status-box
                class="documents-status"
                ref="status-box"
                :errors="errors"
                :successes="successes">
            </status-box>   

            <paginator 
                :itemsToPaginate="documents" v-slot="{itemsToDisplay}">

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
                            v-for="(document) in itemsToDisplay"
                            :id="'item-' + document.id"
                            class="item" >   

                            <td class="preview">
                                <popper
                                    :trigger="popover.trigger"
                                    :options="popover.options"
                                    :delay-on-mouse-over="popover.delayOnMouseOver">
                                    <div class="popper">File type: {{ document.extension ? document.extension : 'unknown' }}</div>
                                
                                    <span slot="reference">
                                        <span :class="'file-icon far fa-2x ' + fileIconFor(document.extension)" />
                                    </span>
                                </popper>
                            </td>
                            <td class="title">
                                <popper
                                    :trigger="popover.trigger"
                                    :options="popover.options"
                                    :delay-on-mouse-over="popover.delayOnMouseOver">
                                    <div class="popper">{{document.title}}</div>
                                    <span slot="reference" class="title-container">
                                        <span class="title-text">{{ document.title }}</span>
                                    </span>
                                </popper>
                            </td>
                            <td class="actions">
                                <div v-if="document.wip"
                                    class="spinner" />
                                <div v-else
                                    class="actions-row">
                                    <popper
                                        :trigger="popover.trigger"
                                        :options="popover.options"
                                        :delay-on-mouse-over="popover.delayOnMouseOver">
                                        <div class="popper">Download</div>
                                        <a  slot="reference"
                                            :id="'download-' + document.id"
                                            class="action download" 
                                            @click="handleDownloadDocument(document)">
                                            <span class="fas fa-download"></span>
                                        </a>
                                    </popper>
                                    <popper
                                        v-if="isInternalUser && document.trashed"
                                        :trigger="popover.trigger"
                                        :options="popover.options"
                                        :delay-on-mouse-over="popover.delayOnMouseOver">
                                        <div class="popper">Restore</div>
                                        <a  slot="reference"
                                            :id="'restore-' + document.id"
                                            class="action restore"
                                            @click="handleRestoreDocument(document)">
                                            <span class="fas fa-trash-restore-alt"></span>
                                        </a>
                                    </popper>
                                    <popper
                                        v-if="isInternalUser && document.trashed" 
                                        :trigger="popover.trigger"
                                        :options="popover.options"
                                        :delay-on-mouse-over="popover.delayOnMouseOver">
                                        <div class="popper">Permenantly Delete</div>
                                        <a  slot="reference"
                                            :id="'delete-' + document.id"
                                            class="action delete"
                                            @click="handleDeleteDocument(document, true)">
                                            <span class="fas fa-times-circle"></span>
                                        </a>
                                    </popper>
                                    <popper
                                        v-if="isInternalUser && !document.trashed"
                                        :trigger="popover.trigger"
                                        :options="popover.options"
                                        :delay-on-mouse-over="popover.delayOnMouseOver">
                                        <div class="popper">Trash</div>
                                        <a  slot="reference"
                                            :id="'trash-' + document.id"
                                            class="action trash"
                                            @click="handleDeleteDocument(document, false)">
                                            <span class="fas fa-trash-alt"></span>
                                        </a>
                                    </popper>
                                    <popper
                                        v-if="isInternalUser && !document.is_shared"
                                        :trigger="popover.trigger"
                                        :options="popover.options"
                                        :delay-on-mouse-over="popover.delayOnMouseOver">
                                        <div class="popper">Share</div>
                                        <a  slot="reference"
                                            :id="'share-' + document.id"
                                            class="action share" 
                                            @click="handleShareDocument(document, true)">
                                            <span class="fas fa-share"></span>
                                        </a>
                                    </popper>
                                    <popper
                                        v-if="isInternalUser && document.is_shared"
                                        :trigger="popover.trigger"
                                        :options="popover.options"
                                        :delay-on-mouse-over="popover.delayOnMouseOver">
                                        <div class="popper">Unshare</div>
                                        <a  slot="reference"
                                            :id="'unshare-' + document.id"
                                            class="action unshare"  
                                            @click="handleShareDocument(document, false)">
                                            <span class="fas fa-share icon-flipped"></span>
                                        </a>
                                    </popper>
                                    <popper
                                        v-if="isInternalUser"
                                        :trigger="popover.trigger"
                                        :options="popover.options"
                                        :delay-on-mouse-over="popover.delayOnMouseOver">
                                        <div class="popper">Tag</div>
                                        <a  slot="reference"
                                            :id="'tag-' + document.id"
                                            class="action tag" 
                                            @click="handleOpenTagger(document.id)">
                                            <span class="fas fa-tags"></span>
                                        </a>
                                    </popper>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </paginator>
           
            <modals-container/>
        </div>
    `,

    data() {
        return {
            popover: {
                trigger: 'hover',
                delayOnMouseOver: 500,
                options: {
                    placement: 'top',
                    modifiers: { offset: { offset: '0,10px' } }
                }
            }
        }
    },

    computed: {
        ...mapGetters(['documents']),
    },

    watch: {

    },

    created() {
    },

    mounted() {
    },

    methods: { 
        async handleDownloadDocument(document) {
            this.clearStatus()
            try {
                const url = await this.fetchDocumentDownloadUrl(document.id)
                window.open(url)
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem downloading document`})
                this.setErrors({errs: messages})
            }
        },

        handleDeleteDocument(document, hardDelete=false) {
            this.clearStatus()
            this.locking(document, async document => {
                try {
                    await this.deleteDocument({document, hardDelete})
                    this.setSuccesses({succs: (hardDelete) ? ["Permanently deleted document"] : ["Trashed document"]})
                } catch (e) {
                    const messages = extractErrors({e, defaultMsg: (hardDelete) ? "Problem permanently deleting document" : "Problem trashing document"})
                    this.setErrors({errs: messages})
                }
            })
        },

        handleRestoreDocument(document) {
            this.clearStatus()
            this.locking(document, async document => {
                try {
                    await this.restoreDocument({document})
                    this.setSuccesses({succs: ["Restored document"]})
                } catch (e) {
                    const messages = extractErrors({e, defaultMsg: "Problem restoring document"})
                    this.setErrors({errs: messages})
                }
            })
        },

        handleShareDocument(document, share=true) {
            this.clearStatus()
            this.locking(document, async document => {
                try {
                    await this.shareDocument({document, share})
                    this.setSuccesses({succs: (share) ? ["Shared document"] : ["Unshared document"]})
                } catch (e) {
                    const messages = extractErrors({e, defaultMsg: (share) ? "Problem sharing document" : "Problem unsharing document"})
                    this.setErrors({errs: messages})
                }
            })
        },

        ...mapActions([
            'fetchDocumentDownloadUrl', 
            'deleteDocument', 
            'restoreDocument', 
            'shareDocument'
        ]),

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