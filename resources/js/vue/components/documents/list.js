import _ from 'lodash'
import Popper from 'vue-popperjs';
import 'vue-popperjs/dist/vue-popper.css';
import {  mapActions } from 'vuex'

import fileIconFor from "./fileicons";
import statusMixin from '../status-box/mixin'
import Paginator from "../pagination/paginator"
import Tagger from "./tagger"
import { List } from 'immutable';

const Component = {

    props: {
        documents: {
            default: () => List()
        }
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
                :successes="successes"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses">
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
        
    },

    watch: {},

    created() {},

    mounted() {},

    methods: { 
        async handleDownloadDocument(document) {
            this.clearStatus()
            this.try("download document", async () => {
                const url = await this.fetchDocumentDownloadUrl(document.id)
                window.open(url)
            })
        },

        handleDeleteDocument(document, hardDelete=false) {
            this.clearStatus()

            const action = (hardDelete)? "permanently delete document" : "trash document"

            this.locking(document, async () =>
                this.try(action, async () => await this.deleteDocument({document, hardDelete}))
            )
        },

        handleRestoreDocument(document) {
            this.clearStatus()

            this.locking(document, async () => {
                this.try("restore document", async () => await this.restoreDocument({document}))
            })
        },

        handleShareDocument(document, share=true) {
            this.clearStatus()

            const action  = (share) ? "share document" : "unshare document"

            this.locking(document, async () => {
                this.try(action, async () => await this.shareDocument({document, share}))
            })
        },

        ...mapActions('documents', [
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
                await func();
            } finally {
                this.$set(document, 'wip', false)
            }
        }
    }
};

export default Component;