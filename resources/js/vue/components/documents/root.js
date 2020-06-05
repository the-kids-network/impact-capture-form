import _ from 'lodash'
import DocumentList from './list'

const Component = {

    props: ["usertype"],

    components: {
        DocumentList
    },

    template: `
        <div>
            <status-box
                class="documents-status"
                :errors="errors"
                :successes="successes">
            </status-box>   
            <document-list
                :usertype="usertype"
                @error="handleErrors($event)"
                @success="handleSuccess($event)">
            </document-list>
        </div>
    `,

    data() {
        return {
            errors: [],
            successes: []
        };
    },

    computed: {
        
    },

    async created() {
    },

    async mounted() {
        
    },

    methods: {
        resetStatusBox() {
            this.errors = [];
            this.successes = [];
        },
        handleErrors(errors) {
            this.resetStatusBox();
            (errors instanceof Array) ? this.errors = errors : this.errors.push(errors)
        },
        handleSuccess(successes) {
            this.resetStatusBox();
            (successes instanceof Array) ? this.successes = successes : this.successes.push(successes)
        }
    },
};


export default Component;
