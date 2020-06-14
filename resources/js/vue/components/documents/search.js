import _ from 'lodash'
import statusMixin from '../status-box/mixin'
import VueTagsInput from '@johmun/vue-tags-input'
import { extractErrors } from '../../utils/api'
import { createNamespacedHelpers } from 'vuex'
import { Set } from 'immutable'
const { mapState, mapActions, mapMutations, mapGetters } = createNamespacedHelpers('documents/search')

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
                    :tags="selectedSearchTagsForVueTagInput"
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
        selectedSearchTagsForVueTagInput() {
            return this.selectedSearchTags.map(i => ({ text: i })).toArray();
        },

        suggestedSearchTagsForVueTagInput() {
            return this.suggestedSearchTags(this.inputText).map(i => ({ text: i }))
        },

        ...mapState(['selectedSearchTags']),
        ...mapGetters(['suggestedSearchTags'])
    },

    watch: {
        selectedSearchTags() {
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
                await this.fetchSuggestedSearchTags()
            } catch(e) {
                const messages = extractErrors({e, defaultMsg: "Problem initialising search tag suggestions"})
                this.addErrors({errs: messages})
            }
        },

        async handleInitialiseDocuments() {
            try {
                await this.fetchDocuments()
            } catch(e) {
                const messages = extractErrors({e, defaultMsg: "Problem initialising documents"})
                this.addErrors({errs: messages})
            }
        },

        async handleSelectSearchTags(tags) {
            try {
                const tagsSet = Set(tags.map(t => t.text))
                await this.selectSearchTags(tagsSet)
            } catch(e) {
                console.log(e)
                const messages = extractErrors({e, defaultMsg: "Problem performing search"})
                this.addErrors({errs: messages})
            }
        },

        async handleClearSearchTags() {
            this.selectSearchTags(null)
        },

        ...mapActions(['fetchDocuments', 'fetchSuggestedSearchTags', 'selectSearchTags'])
    },
};

export default Component;

