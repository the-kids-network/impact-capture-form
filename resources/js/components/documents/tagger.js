import VueTagsInput from '@johmun/vue-tags-input';
import _ from 'lodash'
import {Map, Set} from 'immutable';

const Component = {

    props: ['documentId'],

    components: {
        VueTagsInput,
    },

    template: `
        <div class="document-tagger">
            <status-box class="status" 
                :errors="errors" />

            <div class="entry">
                <div class="description"> Please select tags (maximum of {{ maximumTagsAllowed }}) for the document:</div>
                
                <vue-tags-input class='tags-input'
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
                @click="$emit('close')"><span class="glyphicon glyphicon-ok"></span> Done</span>
        </div>
    `,

    data() {
        return {
            errors: [],
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
        this.setTagsForDocument()
        this.setTagSuggestions()
    },

    async mounted() {
    },

    methods: {
        /**
         * View read/write functions below
         */
        handleError({ messages, disableTagEntry=false } = obj) {
            this.tagEntryDisabled = disableTagEntry
            this.errors = messages
        },

        clearError() {
            this.errors = []
        },

        async setTagSuggestions() {
            this.tagSuggestions = await this.getTagSuggestions()
        },

        async setTagsForDocument() {
            try {
                this.tags = await this.getTagsForDocument(this.documentId)
            } catch (err) {
                this.handleError({
                    messages: ["Failed to get tags for document."],
                    disableTagEntry: true
                })
            }
        },

        async handleCreateTag(tagLabel) {      
            this.clearError()

            // validate tag
            const { valid, reasons } = this.validateTagLabel(this.tags, tagLabel)
            if (!valid) {
                this.handleError({messages: reasons})
                return;
            } 

            // create if valid
            try {
                const keyedCreatedTags = await this.createTagForDocument(this.documentId, tagLabel)
                // update view tags
                this.tags = this.tags.merge(keyedCreatedTags)
                this.tagToEnter = ''
            } catch (err) {
                this.handleError({messages: [`Failed to create tag: ${tagLabel}`]})
            }
        },

        async handleDeleteTag(tagLabel) {
            // find tag id for label
            const tagId = this.tags.get(tagLabel)
            
            try {
                await axios.delete(`/tags/${tagId}`)
                // remove from view tags
                this.tags = this.tags.delete(tagLabel)
            } catch (err) {
                this.handleError({messages: [`Failed to delete tag: ${tagLabel}`]})
            }
        },

        /**
         * Non view state-reading/changing functions below.
         * 
         * These should not modify the view state to remain as pure as possible, and more easily testable in isolation.
         */ 
        async getTagSuggestions() {
            const urlGetTags = `/tags`
            const tags = (await axios.get(
                urlGetTags,
                { params: { resource_type: "document" } }
            )).data
            return Set(tags.map(t => t.label))
        },

        async getTagsForDocument(documentId) {
            const urlGetTags = `/tags`
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
            const createdTagsPayload = (await axios.post(`/tags`, tagsBody)).data
            return Object.assign({}, ...createdTagsPayload.map(t => ({ [t.label]: t.id })))
        },

        async deleteTag(tagId) {        
            return (await axios.delete(`/tags/${tagId}`)).data
        },

        validateTagLabel(existingTags, tagLabel) {
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
                    rule: (existingTags, tagToAdd) => existingTags.size >= this.maximumTagsAllowed,
                    message: `Can only have ${this.maximumTagsAllowed} tags per document (try removing one to make space)`
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
