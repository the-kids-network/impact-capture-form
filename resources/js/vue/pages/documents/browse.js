import _ from 'lodash'
import DocumentSearch from '../../components/documents/search'
import DocumentList from '../../components/documents/list'

const Component = {

    props: [],

    components: {
        DocumentSearch,
        DocumentList
    },

    template: `
        <div>
            <div class="card">
                <div class="card-header">Document Search</div>
                <div class="card-body">
                    <document-search />
                </div>
            </div>
            <div class="card">
                <div class="card-header">Documents</div>
                <div class="card-body">
                    <document-list />
                </div>
            </div>
        </div>
    `,

    data() {
        return {
            
        };
    },

    computed: {
    },

    methods: {
        
    },
};


export default Component;
