import _ from 'lodash'
import SessionReportWorkflow from './workflow';

const Component = {

    props: [],

    components: {
        'session-workflow': SessionReportWorkflow
    },

    template: `
        <div class="card">
            <div class="card-header">
                Session Workflow
            </div>
            <div class="card-body">
                <session-workflow
                    @close="goToSearch">
                </session-workflow>
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
        console.log("mounted- worflow top")
    },

    methods: { 
        goToSearch() {
            this.$router.push({ name: 'root' })
        }
    }
};

export default Component;