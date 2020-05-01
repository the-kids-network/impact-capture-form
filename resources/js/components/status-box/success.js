import _ from 'lodash'

const Component = {

    props: ["messages"],

    components: {
    },

    template: `
        <div 
            v-if="messages.length"
            class="alert alert-success">
            <p v-if="messages.length === 1">
                {{ messages[0] }}
            </p>
            <ul v-else>
                <li v-for="message in messages">{{ message }}</li>
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
