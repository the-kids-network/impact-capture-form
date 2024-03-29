import _ from 'lodash'
import SessionReportSearch from '../../components/session-reports/search';
import SessionReportList from '../../components/session-reports/list';
import SessionReportExport from '../../components/session-reports/export';
import { mapState } from 'vuex';
import { SEARCH_DATE_FORMAT } from '../../utils/date';

const Component = {

    props: {},

    components: {
        'session-report-search': SessionReportSearch,
        'session-report-list': SessionReportList,
        'session-report-export': SessionReportExport
    },

    template: `
        <div>
            <div class="card" v-if="isInternalUser">
                <div class="card-header" data-toggle="collapse" href="#collapsed-find-by-id" role="button" aria-expanded="false" aria-controls="collapsed-find-by-id">
                    Find By ID
                    <span class="float-right find-by-id-toggle"><a>Toggle Find By ID</a></span>
                </div>
                <div id="collapsed-find-by-id" class="card-body collapse">
                    <form 
                        class="find-by-id-form"
                        v-on:keyup.enter.prevent="handleOpenSessionReport(sessionReportId)" 
                        @submit.prevent="handleOpenSessionReport(sessionReportId)">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <input id="idInput" type="text" 
                                        :class="{'form-control form-control-sm': true, 'is-invalid' : !isValidId(sessionReportId)}" 
                                        v-model="sessionReportId">
                                <div class="invalid-feedback invalid-id">Type a valid numerical ID</div>
                            </div>
                            <div class="form-group col-md-3">
                                <span v-on:click="handleOpenSessionReport(sessionReportId)" class="search btn btn-primary btn-sm">
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
                <div id="collapsed-search" class="card-body collapse show">
                    <session-report-search 
                        :searchCriteria="searchParams"
                        @searchCriteria="searchParams = $event">
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
                        @sessionReportSelected="handleSelectSessionReport($event)">
                    </session-report-list>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Session Reports Export
                </div>
                <div class="card-body">
                    <session-report-export 
                        label="Export your search results"
                        :searchParams="searchParams"/>
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
        ...mapState('sessionReports', {
            searchResults: 'reports'
        }),

        searchParams: {
            get() {
                return this.getSearchParameters()
            },
            set(params) {
                this.setSearchParameters(params)
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

        // View handlers
        handleOpenSessionReport(sessionReportId) {
            if (this.isValidId(sessionReportId)) {
                this.$router.push({ name: 'session-reports-view-one', params: { 'sessionReportId': sessionReportId } })
            }
        }, 
        handleSelectSessionReport(sessionReportId) {
            this.$store.commit('sessionReports/setCurrentSessionReportId', sessionReportId)
            this.$router.push({ name: 'session-reports-workflow' })
        },

        // Search parameter functions
        hasSearchParameters() {
            return ! _.isEmpty(this.$route.query)
        },
        setSearchParameters(params, type="push") {
            const cleanedParams = _.omitBy(params, _.isNil)
            const routeProps = { path: this.$route.path, query: cleanedParams }
            "replace" === type ? this.$router.replace(routeProps) : this.$router.push(routeProps)
        },
        getSearchParameters() {
            const {mentor_id, mentee_id, session_rating_id, safeguarding_id, session_date_range_start, session_date_range_end} = this.$route.query

            return {
                mentor_id: !_.isNil(mentor_id) ? _.parseInt(mentor_id) : null,
                mentee_id: !_.isNil(mentee_id) ? _.parseInt(mentee_id) : null,
                session_rating_id: !_.isNil(session_rating_id) ? _.parseInt(session_rating_id): null,
                safeguarding_id: !_.isNil(safeguarding_id) ? _.parseInt(safeguarding_id) : null,
                session_date_range_start: !_.isNil(session_date_range_start) ? session_date_range_start : null,
                session_date_range_end: !_.isNil(session_date_range_end) ? session_date_range_end : null
            }
        },
        buildDefaultSearchParameters() {
            return {
                // last one month by default for performance reasons
                session_date_range_start : moment().subtract(1, 'months').format(SEARCH_DATE_FORMAT),
                session_date_range_end: moment().format(SEARCH_DATE_FORMAT)
            }
        }
    }
};

export default Component;