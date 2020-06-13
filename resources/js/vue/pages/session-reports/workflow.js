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
            <div class="container session-report-workflow">
                <div class="row page-nav">
                    <div class="navigation-buttons col-8 mt-auto mb-auto text-left ">
                        <span :class="{'first-report btn btn-primary btn-sm': true, 'disabled': !firstSessionReport}" 
                                type="button" 
                                aria-label="Beginning"
                                @click='goToSessionReport(firstSessionReport)'><span class="fas fa-fast-backward" /></span>
                        <span :class="{'previous-report btn btn-primary btn-sm': true, 'disabled': !previousSessionReport}" 
                                type="button" 
                                aria-label="Previous"
                                @click='goToSessionReport(previousSessionReport)'><span class="fas fa-backward" /></span>
                        <span :class="{'next-report btn btn-primary btn-sm': true, 'disabled': !nextSessionReport}" 
                                type="button" 
                                aria-label="Next"
                                @click='goToSessionReport(nextSessionReport)'><span class="fas fa-forward" /></span>
                        <span :class="{'last-report btn btn-primary btn-sm': true, 'disabled': !lastSessionReport}" 
                                type="button" 
                                aria-label="End"
                                @click='goToSessionReport(lastSessionReport)'><span class="fas fa-fast-forward" /></span>
                    </div>
                    <div class="col mt-auto mb-auto text-right close-workflow">
                        <a type="button" class="btn btn-link" @click.prevent="closeWorkflow">Close</a>
                    </div>
                </div>
            </div>
            
            <div>
                <div v-if="currentSessionReportId">
                    <session-view-toggler  :session-report-id="currentSessionReportId" />
                </div>
                <div v-else class="card">
                    <div class="card-body">
                        <span>No session reports selected for workflow. <a type="button" @click.prevent="goToSearch">Try searching again</a>.</span>
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
        currentSessionReportId: {
            get () {
                return this.$store.getters.currentSessionReportId
            },
            set (value) {
                this.$store.commit('setCurrentSessionReportId', value)
            }
        },
        firstSessionReport: {
            get () {
                return this.$store.getters.firstSessionReport
            }
        },
        lastSessionReport: {
            get () {
                return this.$store.getters.lastSessionReport
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
            if (sessionReportId) this.currentSessionReportId = sessionReportId
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