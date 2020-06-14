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
            <document-search />

            <document-list />
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
