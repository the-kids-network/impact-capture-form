import _ from 'lodash'

const Component = {

    props: ["errors"],

    components: {
    },

    template: `
        <div v-if="errors.length"
             class="alert alert-danger">

            <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                @click="$emit('clear')">
                <span aria-hidden="true">&times;</span>
            </button>

            <div v-for="error in errors"> 
                <section>
                    <span><strong>{{error.rootMessage}}</strong></span>
                    <ul>
                        <li v-for="msg in error.messages">
                            {{ msg }}
                        </li>
                    </ul>
                </section>
            </div>
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
