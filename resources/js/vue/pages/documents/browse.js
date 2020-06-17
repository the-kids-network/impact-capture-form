import _ from 'lodash'
import { mapActions, mapGetters } from 'vuex'

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
                    <document-list :documents="documents"/>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
            
        };
    },

    computed: {
        ...mapGetters('documents', {
            documents: 'documentsFiltered'
        }),
    },

    methods: {
        
    },
};


export default Component;
