import _ from 'lodash'
import Error from './error'
import Success from './success'

const Component = {

    props: {
        errors: {
            default: () => []        
        },
        successes: {
            default: () => []
        }
    },

    components: {
        Error,
        Success
    },

    template: `
        <div>
            <error :errors="errors" />
            <success :messages="successes" />
        </div>
    `,

    data() {
        return {

        };
    },

    computed: {
        
    },

    created() {
    },

    mounted() {
        
    },

    methods: {
        
    },
};


export default Component;
