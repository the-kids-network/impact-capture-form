const Component = {

    props: {
        mentors: {
            default: () => []
        }
    },

    components: {
    },

    template: `
        <div>
            <div class="card">
                <div class="card-header">
                    <span>404 - that page does not exist</span>
                </div>
                <div class="card-body">
                    <a href="/home" class="btn btn-primary">Back to Home</a>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
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