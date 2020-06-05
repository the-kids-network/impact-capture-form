import _ from 'lodash'
import SessionReportSearch from './search';
import SessionReportList from './list';
import SessionReportWorkflow from './workflow';

const Component = {

    props: ['mentors'],

    components: {
        'session-report-search': SessionReportSearch,
        'session-report-list': SessionReportList,
        'session-workflow': SessionReportWorkflow
    },

    template: `
        <div>
            <div v-if="workflowEnabled">
                <div class="card">
                    <div class="card-header">
                        Session Workflow
                    </div>
                    <div class="card-body">
                        <session-workflow
                            :session-reports=sessionReports
                            :initial-session-report-id=sessionReportToStartAt
                            @close="workflowEnabled = false">
                        </session-workflow>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="card">
                    <div class="card-header">
                        Session Search
                        <span class="float-right"><a class="expand-all" data-toggle="collapse" href="#collapsed-content" role="button" aria-expanded="false" aria-controls="collapsed-content">Toggle Search</a></span>
                    </div>
                    <div id="collapsed-content" class="card-body collapse show">
                        <session-report-search 
                            :mentors=mentors
                            :search-criteria=searchCriteria
                            @results="setSessionReports($event)">
                        </session-report-search>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        Session Reports
                    </div>
                    <div class="card-body">
                        <session-report-list
                            :session-reports=sessionReports
                            @sessionReportSelected="startWorkflowAtSessionReport($event)">
                        </session-report-list>
                    </div>
                </div>
            </div>
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
    },

    methods: { 
        setSessionReports(results) {
            this.sessionReports = results.sessionReports
            // cache the search criteria to restore later
            this.searchCriteria = results.searchCriteria
        },

        startWorkflowAtSessionReport(sessionReportId) {
            this.sessionReportToStartAt = sessionReportId
            this.workflowEnabled = true
        }
    }
};

export default Component;