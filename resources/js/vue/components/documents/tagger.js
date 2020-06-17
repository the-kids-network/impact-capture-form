import _ from 'lodash'
import { Map, Set } from 'immutable';
import VueTagsInput from '@johmun/vue-tags-input';
import statusMixin from '../status-box/mixin'

const Component = {

    props: ['documentId'],

    mixins: [statusMixin],

    components: {
        VueTagsInput,
    },

    template: `
        <div class="tagger">
            <status-box 
                ref="status-box"
                class="status" 
                :successes="successes"
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses" />

            <div class="entry">
                <div class="description"> Please select tags (maximum of {{ maximumTagsAllowed }}) for the document:</div>
                
                <vue-tags-input class='documents tags-input'
                    v-model="tagToEnter"
                    :disabled="tagEntryDisabled"
                    :tags="_tags"
                    :autocomplete-items="_tagSuggestions"
                    :autocomplete-min-length="autocompleteMinLength"
                    @before-adding-tag="obj => handleCreateTag(obj.tag.text)"
                    @before-deleting-tag="obj => handleDeleteTag(obj.tag.text)"
                    />
            </div>

            <div class="spacer" />

            <span class="done btn btn-primary" 
                @click="$emit('close')"><span class="fas fa-check"></span> Done</span>
        </div>
    `,

    data() {
        return {
            // tag input config
            tagEntryDisabled: false,
            autocompleteMinLength:0,
            maximumTagsAllowed: 5,

            // tag input key state
            tagToEnter: '',
            tags: Map(),
            tagSuggestions: Set(),
          };
    },

    computed: {
        _tags() {
            return this.tags
                .keySeq().map(tagLabel => ({ text: tagLabel}))
                .toArray()
        },
        _tagSuggestions() {
            return this.tagSuggestions
                // filter to entered characters
                .filter(i => (i.toLowerCase().indexOf(this.tagToEnter.toLowerCase()) !== -1))
                .map(autoSuggestion => ({ text: autoSuggestion }))
                .toArray();
        },
    },

    async created() {
        this.initialiseTagsForDocument()
        this.initialiseTagSuggestions()
    },

    async mounted() {
    },

    methods: {
        /**
         * View read/write functions below
         */
        disableTagEntry() {
            this.tagEntryDisabled = true
        },

        async initialiseTagSuggestions() {
            this.tagSuggestions = await this.fetchTagSuggestions()
        },

        async initialiseTagsForDocument() {
            await this.try("get existing tags for document",
                async () => this.tags = await this.fetchTagsForDocument(this.documentId)
            )

            if (this.errors.length) {
                this.disableTagEntry()
            }
        },

        async handleCreateTag(tagLabel) {      
            this.clearErrors()

            // validate tag
            const { valid, reasons } = this.validateTagLabel(this.tags, tagLabel, this.maximumTagsAllowed)
            if (!valid) {
                this.addError({rootMessage: "Failure --> invalid tag", messages: reasons })
                return;
            } 

            // create if valid
            this.try("create tag",
                async () => {
                    const keyedCreatedTags = await this.createTagForDocument(this.documentId, tagLabel)
                    // update view tags
                    this.tags = this.tags.merge(keyedCreatedTags)
                    this.tagToEnter = ''
                }
            )
        },

        async handleDeleteTag(tagLabel) {
            // find tag id for label
            const tagId = this.tags.get(tagLabel)
            
            this.try(`delete tag:  ${tagLabel}`,
                async () => {
                    await this.deleteTag(tagId)
                    this.tags = this.tags.delete(tagLabel)
                }
            )
        },

        /*
        * Functions that do not interact with component state directly
        */
        async fetchTagSuggestions() {
            const urlGetTags = `/api/tags`
            const tags = (await axios.get(
                urlGetTags,
                { params: { resource_type: "document" } }
            )).data
            return Set(tags.map(t => t.label))
        },

        async fetchTagsForDocument(documentId) {
            const urlGetTags = `/api/tags`
            const params = {
                resource_type: "document",
                resource_id: `${documentId}`
            }
            const tagsPayload = (await axios.get(urlGetTags, { params: params })).data
            const tags = Object.assign({}, ...tagsPayload.map(t => ({ [t.label]: t.id })))
            return Map(tags)
        },

        async createTagForDocument(documentId, tagLabel) {        
            const tagsBody = [
                {
                    tagged_item: {
                        resource_type: 'document',
                        resource_id: `${documentId}`
                    },
                    tag_label: tagLabel
                }
            ];
            const createdTagsPayload = (await axios.post(`/api/tags`, tagsBody)).data
            return Object.assign({}, ...createdTagsPayload.map(t => ({ [t.label]: t.id })))
        },

        async deleteTag(tagId) {        
            return (await axios.delete(`/api/tags/${tagId}`)).data
        },

        validateTagLabel(existingTags, tagLabel, maximumTagsAllowed) {
            const rules = {
                duplicate : {
                    rule: (existingTags, tagToAdd)  => existingTags.keySeq().contains(tagToAdd),
                    message: "Entered tag is a duplicate"
                },
                tooLong : {
                    rule: (existingTags, tagToAdd)  => tagToAdd.length > 15,
                    message: "Tag cannot be more than 10 characters long"
                },
                tagLimitReached: {
                    rule: (existingTags, tagToAdd) => existingTags.size >= maximumTagsAllowed,
                    message: `Can only have ${maximumTagsAllowed} tags per document (try removing one to make space)`
                }
            }

            const validationFailures = Object.keys(rules)
                .filter(ruleKey => rules[ruleKey].rule(existingTags, tagLabel))
                .map(failedRuleKey => rules[failedRuleKey].message);

            return validationFailures.length 
                ? { valid: false, reasons: validationFailures} 
                : { valid: true }
        },
    },
};


export default Component;
