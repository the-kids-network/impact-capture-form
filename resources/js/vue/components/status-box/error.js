import _ from 'lodash'

const Component = {

    props: ["errors"],

    components: {
    },

    template: `
        <div 
            v-if="errors.length"
            class="alert alert-danger">
            <p v-if="errors.length === 1">
                {{ errors[0] }}
            </p>

            <ul v-else>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
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
