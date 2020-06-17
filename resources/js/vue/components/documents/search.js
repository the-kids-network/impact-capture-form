import _ from 'lodash'
import VueTagsInput from '@johmun/vue-tags-input'
import { mapState, mapActions, mapGetters } from 'vuex'
import { Set } from 'immutable'

import { extractErrors } from '../../utils/api'
import statusMixin from '../status-box/mixin'

const Component = {

    props: [],

    mixins: [statusMixin],

    components: {
        VueTagsInput,
    },

    template: `
        <div class="documents-search">
            <status-box 
                ref="status-box"
                class="status" 
                :successes="successes"
                :errors="errors" />

            <div class="search-bar">
                <vue-tags-input class='documents tags-input'
                    v-model="inputText"
                    placeholder="Add Search Tag"
                    :tags="searchTagsForVueTagInput"
                    :max-tags="maximumTagsAllowed"
                    :autocomplete-items="suggestedSearchTagsForVueTagInput"
                    :autocomplete-min-length="autoCompleteMinLength"
                    :add-only-from-autocomplete="autoCompleteStrict"
                    @tags-changed="newTags => handleSelectSearchTags(newTags)"/>

                <div class="buttons">
                    <span class="clear btn btn-light" 
                        @click="handleClearSearchTags"><span class="fas fa-times"></span> Reset</span>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
            // vue-tags-input config
            autoCompleteMinLength:0,
            autoCompleteStrict: true,
            maximumTagsAllowed: 5,

            // tag input state
            inputText: '',    
        };
    },

    computed: {
        ...mapState('documents/search', ['searchTags']),
        ...mapGetters('documents/search', ['suggestedSearchTags']),

        searchTagsForVueTagInput() {
            return this.searchTags.map(i => ({ text: i })).toArray();
        },

        suggestedSearchTagsForVueTagInput() {
            return this.suggestedSearchTags(this.inputText).map(i => ({ text: i }))
        },
    },

    watch: {
        searchTags() {
           this.clearStatus()
       },
    },

    async created() {
        await this.handleInitialiseDocuments()
        this.handleInitialiseSuggestedSearchTags()
    },

    async mounted() {
        
    },

    methods: {
        async handleInitialiseSuggestedSearchTags() {
            try {
                await this.initialiseSuggestedSearchTags()
            } catch(e) {
                const messages = extractErrors({e, defaultMsg: "Problem initialising search tag suggestions"})
                this.addErrors({errs: messages})
            }
        },

        async handleInitialiseDocuments() {
            try {
                await this.initialiseDocuments()
            } catch(e) {
                const messages = extractErrors({e, defaultMsg: "Problem initialising documents"})
                this.addErrors({errs: messages})
            }
        },

        async handleSelectSearchTags(tags) {
            try {
                const tagsSet = Set(tags.map(t => t.text))
                await this.submitSearchTags(tagsSet)
            } catch(e) {
                const messages = extractErrors({e, defaultMsg: "Problem performing search"})
                this.addErrors({errs: messages})
            }
        },

        async handleClearSearchTags() {
            this.submitSearchTags(null)
        },

        ...mapActions('documents', ['initialiseDocuments']),
        ...mapActions('documents/search', ['initialiseSuggestedSearchTags', 'submitSearchTags'])
    },
};

export default Component;

