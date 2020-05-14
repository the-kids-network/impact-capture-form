import VueTagsInput from '@johmun/vue-tags-input';
import _ from 'lodash'
import {Set, Map} from 'immutable';

const Component = {

    props: [],

    components: {
        VueTagsInput,
    },

    template: `
        <div class="search">
            <vue-tags-input class='tags-input'
                v-model="inputText"
                placeholder="Add Search Tag"
                :tags="_tagsSelected"
                :max-tags="maximumTagsAllowed"
                :autocomplete-items="_tagSuggestions"
                :autocomplete-min-length="autoCompleteMinLength"
                :add-only-from-autocomplete="autoCompleteStrict"
                @tags-changed="newTags => updateSelectedTags(newTags)"
                />

            <span :class="'submit btn btn-primary ' + (!_searchEnabled ? 'disabled' : '')" 
                @click="submitSearch"><span class="glyphicon glyphicon-search"></span> Search</span>
            <span class="clear btn btn-secondary" 
                @click="clearSearch"><span class="glyphicon glyphicon-remove"></span> Reset</span>
        </div>
    `,

    data() {
        return {
            autoCompleteMinLength:0,
            autoCompleteStrict: true,
            maximumTagsAllowed: 5,

            // tag input key state
            inputText: '',
            tagsSelected: Set(),
            tagSuggestions: Set(),
        };
    },

    computed: {
        _tagsSelected() {
            return this.tagsSelected
                .map(i => ({ text: i }))
                .toArray();
        },

        _tagSuggestions() {
            return this.tagSuggestions
                // filter to entered characters
                .filter(i => (i.toLowerCase().indexOf(this.inputText.toLowerCase()) !== -1))
                .map(i => ({ text: i }))
                .toArray();
        },

        _searchEnabled() {
            return this.tagsSelected.size
        }
    },

    async created() {
        this.setTagSuggestions()
    },

    async mounted() {
        
    },

    methods: {
        async setTagSuggestions() {
            if (!this.tagsSelected.size) {
                // if no tags selected, show all possible tag labels that can be selected
                this.tagSuggestions = await this.getAllTags()
            } else {
                // if tags selected, show associated tags to those selected
                const associations = await this.getAssociatedTagsFor(this.tagsSelected.toArray())
                this.tagSuggestions = Set.intersect(associations.toSetSeq())
            }
        },

        updateSelectedTags(newTags) {
            this.tagsSelected = Set(newTags.map(t => t.text))
            this.setTagSuggestions();
        },

        async submitSearch() {
            if (!this._searchEnabled) return;

            // reset any errors for new search
            this.$emit('error', [])

            try {
                const matchingDocumentIds = await this.getDocumentsMatchingTags(this.tagsSelected.toArray());
                // send to parent component to handle filtering based on results
                this.$emit('results', matchingDocumentIds)
            } catch(e) {
                this.$emit('error', "Search failed.")
            }
        },

        async clearSearch() {
            this.tagsSelected = Set()
            this.setTagSuggestions();
            // send event to parent to clear filtering
            this.$emit('error', [])
            this.$emit('clear')
        },

        /*
        * Functions that do not interact with component state directly
        */
        async getAllTags() {
            const tags = (await axios.get(
                `/tags`,
                { params: { resource_type: "document" } }
            )).data
            return Set(tags.map(t => t.label))
        },

        async getAssociatedTagsFor(tags) {
            const data = (await axios.get(
                `/tag-labels/associated`,
                { params: { tag_labels: tags } }
            )).data
            return Map(data);
        },

        async getDocumentsMatchingTags(tags) {
            const data = (await axios.get(
                `/tagged-items`,
                { 
                    params: { 
                        resource_type: "document",
                        tag_labels: tags 
                    } 
                }
            )).data

            return data.map(ti => ti.resource_id)
        }
    },
};


export default Component;

