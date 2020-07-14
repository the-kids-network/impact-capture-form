import _ from 'lodash'
import { List } from 'immutable';
import { mapActions } from 'vuex'

import SessionReportView from '../../components/session-reports/view'
import SessionReportEdit from '../../components/session-reports/edit'
import ExpenseClaimList from '../../components/expenses/list'

import statusMixin from '../../components/status-box/mixin'

const Component = {

    props: ['sessionReportId'],

    mixins: [statusMixin],

    components: {
        'session-report-view': SessionReportView,
        'session-report-edit': SessionReportEdit,
        'expense-claim-list': ExpenseClaimList
    },

    template: `
        <div class="session-manage-view-toggler">
            <div class="card">
                <div class="card-header">
                    Session Report: {{ sessionReportId }}
                </div>
                <div class="card-body">
                    <div class="row mode-selector" v-if="isInternalUser">
                        <div class="col-md mt-auto mb-auto text-left">
                            <span :class="{'edit-report btn btn-primary btn-sm': true, 'disabled': false}" 
                                    type="button" 
                                    @click="switchMode('edit')">Edit</span>
                            <span :class="{'view-report btn btn-primary btn-sm': true, 'disabled': false}" 
                                    type="button" 
                                    @click="switchMode('view')">View</span>
                        </div>
                    </div>

                    <session-report-view
                        v-if="mode === 'view'"
                        :session-report-id=sessionReportId>
                    </session-report-view>
                    
                    <session-report-edit
                        v-if="mode === 'edit'"
                        :session-report-id=sessionReportId
                        @sessionReportDeleted="tryInitialiseAssociatedExpenseClaims">
                    </session-report-edit>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Associated Expense Claims
                </div>
                <div class="card-body">
                    <status-box
                        ref="status-box"
                        class="status"
                        :successes="successes"
                        :errors="errors"
                        @clearErrors="clearErrors"
                        @clearSuccesses="clearSuccesses">
                    </status-box>   
                    <expense-claim-list 
                        :expense-claims="associatedExpenseClaims"
                        @claimSelected="goToExpenseClaim" />
                </div>
            </div>
        </div>
    `,

    data() {
        return {
            mode: "view",
            associatedExpenseClaims: List()
        }
    },

    computed: {
        
    },

    watch: {
        sessionReportId() {
            this.tryInitialiseAssociatedExpenseClaims()
        }
    },

    created() {
        this.tryInitialiseAssociatedExpenseClaims()
    },

    mounted() {
    },

    methods: { 
        switchMode(mode) {
            this.mode = mode
        },

        goToExpenseClaim(claimId) {
            this.$router.push({ name: 'expense-claims-view-one', params: { 'expenseClaimId': claimId } })
        },
        
        async tryInitialiseAssociatedExpenseClaims() {
            this.associatedExpenseClaims = List()
            this.try("get associated expense claims", 
                async () => this.associatedExpenseClaims = List(await this.fetchExpenseClaimsForSessionReport(this.sessionReportId))
            )
        },

        ...mapActions('expenses', ['fetchExpenseClaimsForSessionReport'])
    },
};

export default Component;