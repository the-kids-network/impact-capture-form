import _ from 'lodash'
import SessionReportSearch from '../../components/session-reports/search';
import SessionReportList from '../../components/session-reports/list';
import { SEARCH_DATE_FORMAT } from '../../components/session-reports/consts'

const Component = {

    props: {
        
    },

    components: {
        'session-report-search': SessionReportSearch,
        'session-report-list': SessionReportList,
    },

    template: `
        <div>
            <div class="card">
                <div class="card-header" data-toggle="collapse" href="#collapsed-find-by-id" role="button" aria-expanded="false" aria-controls="collapsed-find-by-id">
                    Find By ID
                    <span class="float-right"><a>Toggle Find By ID</a></span>
                </div>
                <div id="collapsed-find-by-id" class="card-body collapse">
                    <form 
                        v-on:keyup.enter.prevent="openSessionReport(sessionReportId)" 
                        @submit.prevent="openSessionReport(sessionReportId)">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input id="idInput" type="text" 
                                        :class="{'form-control form-control-sm': true, 'is-invalid' : !isValidId(sessionReportId)}" 
                                        v-model="sessionReportId">
                                <div class="invalid-feedback invalid-id">Type a valid numerical ID</div>
                            </div>
                            <div class="form-group col-md-3">
                                <span v-on:click="openSessionReport(sessionReportId)" class="search btn btn-primary btn-sm">
                                <span class="fas fa-chevron-circle-right" /> Go</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header" data-toggle="collapse" href="#collapsed-search" role="button" aria-expanded="false" aria-controls="collapsed-search">
                    Session Search
                    <span class="float-right"><a>Toggle Search</a></span>
                </div>
                <div id="collapsed-search" class="card-body collapse">
                    <session-report-search 
                        :searchCriteria="searchParams"
                        @searchCriteria="searchParams = $event"
                        @searchResults="searchResults = $event">
                    </session-report-search>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Session Reports
                </div>
                <div class="card-body">
                    <session-report-list
                        :sessionReports="searchResults"
                        @sessionReportSelected="sessionReportSelected($event)">
                    </session-report-list>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
           sessionReportId: null
        }
    },

    computed: {
        searchParams: {
            get() {
                return this.getSearchParameters()
            },
            set(params) {
                this.setSearchParameters(params)
            }
        },
        searchResults: {
            get() {
                return this.$store.state.sessionReportSearch.list
            },
            set(sessionReports) {
                this.$store.commit('setSessionReports', sessionReports)
            }
        },
    },

    watch: {
        
    },

    created() {
        if (!this.hasSearchParameters()) {
            // replace route rather than push a new route to history
            this.setSearchParameters(this.buildDefaultSearchParameters(), "replace")
        }
    },

    mounted() {
    },

    methods: {
        
        isValidId(sessionReportId) {
            return /^\d+$/.test(sessionReportId)
        },
        openSessionReport(sessionReportId) {
            if (this.isValidId(sessionReportId)) {
                this.$router.push({ name: 'session-reports-view-one', params: { 'sessionReportId': sessionReportId } })
            }
        }, 
        sessionReportSelected(sessionReportId) {
            this.$store.commit('setCurrentSessionReport', sessionReportId)
            this.$router.push({ name: 'session-reports-workflow' })
        },


        // Search parameter functions
        hasSearchParameters() {
            return ! _.isEmpty(this.$route.query)
        },
        setSearchParameters(params, type="push") {
            const {mentorId, menteeId, sessionDateRangeStart, sessionDateRangeEnd} = params

            const queryParamsToAdd = { 
                ...(mentorId ? {'mentor_id': mentorId}: {}),
                ...(menteeId ? {'mentee_id': menteeId}: {}),
                ...(sessionDateRangeStart ? {'session_date_range_start': sessionDateRangeStart}: {} ),
                ...(sessionDateRangeEnd ? {'session_date_range_end': sessionDateRangeEnd}: {} ),
            }

            // save as url query parameters
            const routeProps = { path: this.$route.path, query: queryParamsToAdd }
            if ("replace" === type) {
                this.$router.replace(routeProps)
            } else {
                this.$router.push(routeProps)
            }
        },
        getSearchParameters() {
            const {mentor_id, mentee_id, session_date_range_start, session_date_range_end} = this.$route.query

            return {
                ...(mentor_id ? {mentorId: parseInt(mentor_id)}: {}),
                ...(mentee_id ? {menteeId: parseInt(mentee_id)}: {}),
                ...(session_date_range_start ? {sessionDateRangeStart: session_date_range_start}: {} ),
                ...(session_date_range_end ? {sessionDateRangeEnd: session_date_range_end}: {} ),
            }
        },
        buildDefaultSearchParameters() {
            return {
                // last one month
                sessionDateRangeStart : moment().subtract(1, 'months').format(SEARCH_DATE_FORMAT),
                sessionDateRangeEnd: moment().format(SEARCH_DATE_FORMAT)
            }
        }
    }
};

export default Component;