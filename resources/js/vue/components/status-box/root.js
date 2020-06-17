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
            <div class="top-of-status" ref="top-of-status" />
            <error :errors="errors" 
                    @clear="$emit('clearErrors')"/>
            <success :messages="successes" 
                    @clear="$emit('clearSuccesses')"/>
            <div class="bottom-of-status" ref="bottom-of-status" />
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
