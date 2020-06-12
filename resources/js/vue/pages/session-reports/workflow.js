import _ from 'lodash'
import SessionReportViewToggler from './view-toggler';

const Component = {

    props: {
        
    },

    components: {
        'session-view-toggler': SessionReportViewToggler,
    },

    template: `
        <div>
            <div class="container">
                <div class="row page-nav">
                    <div class="col-4 mt-auto mb-auto text-left back-forward">
                        <span :class="{'btn btn-primary btn-sm': true, 'disabled': !previousSessionReport}" 
                                type="button" 
                                aria-label="Previous"
                                @click='goToSessionReport(previousSessionReport)'><span class="fas fa-backward" /></span>
                        <span :class="{'btn btn-primary btn-sm': true, 'disabled': !nextSessionReport}" 
                                type="button" 
                                aria-label="Next"
                                @click='goToSessionReport(nextSessionReport)'><span class="fas fa-forward" /></span>
                    </div>
                    <div class="col mt-auto mb-auto text-right close-workflow">
                        <a type="button" @click="closeWorkflow">Close workflow</a>
                    </div>
                </div>
            </div>
            
            <div>
                <div v-if="currentSessionReport">
                    <session-view-toggler  :session-report-id="currentSessionReport" />
                </div>
                <div v-else class="card">
                    <div class="card-body">
                        <span>No session reports selected for workflow. <a type="button" @click="goToSearch">Try searching again</a>.</span>
                    </div>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
        }
    },

    computed: {
        currentSessionReport: {
            get () {
                return this.$store.getters.currentSessionReport
            },
            set (value) {
                this.$store.commit('setCurrentSessionReport', value)
            }
        },
        previousSessionReport: {
            get () {
                return this.$store.getters.previousSessionReport
            }
        },

        nextSessionReport: {
            get () {
                return this.$store.getters.nextSessionReport
            }
        }
    },

    watch: {
        
    },

    created() {
    },

    mounted() {
    },

    methods: { 
        goToSessionReport(sessionReportId) {
            if (sessionReportId) this.currentSessionReport = sessionReportId
        },
        closeWorkflow() {    
            this.$router.go(-1)
        },
        goToSearch() {    
            this.$router.push({ name: 'session-reports-search'})
        }
    },
};

export default Component;