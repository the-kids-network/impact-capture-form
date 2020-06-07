import _ from 'lodash'

const Component = {

    props: ['mentors'],

    components: {
       
    },

    template: `
        <div>
            <router-view name="session-reports" :mentors="mentors"></router-view>
        </div>
    `,

    data() {
        return {
           searchCriteria: null,
           sessionReports: [],

           // workflow
           workflowEnabled: false,
           sessionReportToStartAt: null
        }
    },

    computed: {
        
    },

    watch: {
        
    },

    async created() {
    },

    mounted() {
        console.log("mount - index")
    },

    methods: { 

    }
};

export default Component;