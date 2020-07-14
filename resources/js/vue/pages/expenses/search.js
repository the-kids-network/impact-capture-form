import _, { isArray } from 'lodash'
import ExpenseClaimSearch from '../../components/expenses/search';
import ExpenseClaimsList from '../../components/expenses/list';
import ExpenseClaimExport from '../../components/expenses/export';
import { SEARCH_DATE_FORMAT } from '../../utils/date';
import { mapState } from 'vuex';

const Component = {

    props: {},

    components: {
        'expense-claim-search': ExpenseClaimSearch,
        'expense-claim-list': ExpenseClaimsList,
        'expense-claim-export': ExpenseClaimExport
    },

    template: `
        <div>
            <div class="card">
                <div class="card-header" data-toggle="collapse" href="#collapsed-search" role="button" aria-expanded="false" aria-controls="collapsed-search">
                    Expense Claim Search
                    <span class="float-right"><a>Toggle Search</a></span>
                </div>
                <div id="collapsed-search" class="card-body collapse show">
                    <expense-claim-search 
                        :searchCriteria="searchParams"
                        @searchCriteria="searchParams = $event">
                    </expense-claim-search>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Expense Claims
                </div>
                <div class="card-body">
                    <expense-claim-list
                        :expenseClaims="searchResults"
                        @claimSelected="handleSelectExpenseClaim($event)">
                    </expense-claim-list>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Expense Claims Export
                </div>
                <div class="card-body">
                    <expense-claim-export 
                        label="Export your search results"
                        :searchParams="searchParams"/>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
        }
    },

    computed: {
        ...mapState('expenses', {
            searchResults: 'claims'
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
        
        handleSelectExpenseClaim(expenseClaimId) {
            this.$store.commit('expenses/setCurrentClaimId', expenseClaimId)
            this.$router.push({ name: 'expenses-workflow' })
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
            const {mentor_id, status, created_date_range_start, created_date_range_end} = this.$route.query

            return {
                mentor_id: !_.isNil(mentor_id) ? _.parseInt(mentor_id) : null,
                status: !_.isNil(status) 
                                ? isArray(status) ? status : [status]
                                : [],
                created_date_range_start: !_.isNil(created_date_range_start) ? created_date_range_start : null,
                created_date_range_end: !_.isNil(created_date_range_end) ? created_date_range_end : null
            }
        },
        buildDefaultSearchParameters() {
            return {
                // last one month by default for performance reasons
                created_date_range_start : moment().subtract(1, 'months').format(SEARCH_DATE_FORMAT),
                created_date_range_end: moment().format(SEARCH_DATE_FORMAT)
            }
        }
    }
};

export default Component;