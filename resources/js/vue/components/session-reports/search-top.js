import _ from 'lodash'
import SessionReportSearch from './search';
import SessionReportList from './list';

const Component = {

    props: ['mentors'],

    components: {
        'session-report-search': SessionReportSearch,
        'session-report-list': SessionReportList,
    },

    template: `
        <div>
            <div class="card">
                <div class="card-header">
                    Session Search
                    <span class="float-right"><a class="expand-all" data-toggle="collapse" href="#collapsed-content" role="button" aria-expanded="false" aria-controls="collapsed-content">Toggle Search</a></span>
                </div>
                <div id="collapsed-content" class="card-body collapse show">
                    <session-report-search :mentors=mentors>
                    </session-report-search>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Session Reports
                </div>
                <div class="card-body">
                    <session-report-list
                        @sessionReportSelected="startWorkflow">
                    </session-report-list>
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

    async created() {
    },

    mounted() {
        console.log("mounted- search top")
    },

    methods: { 
        startWorkflow() {
            this.$router.push({ name: 'workflow' })
        }
    }
};

export default Component;