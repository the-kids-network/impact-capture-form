import _ from 'lodash'

const Component = { 

    props: {
    },

    components: {
 
    },

    template: `
        <div>
            <p>Not inline: {{filteredList}}</p>

            <slot :filteredList="filteredList" :somethingElse="somethingElse"/>
        </div>
    `,

    data() {
        return {
            filteredList: ['a'],
            somethingElse: "def"
        }
    },

    computed: {
        
    },

    watch: {
    
    },

    created() {
        
    },

    mounted() {
    },

    methods: { 
    }
};

export default Component;