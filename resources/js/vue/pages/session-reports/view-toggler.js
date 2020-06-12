import SessionReportView from '../../components/session-reports/view'
import SessionReportEdit from '../../components/session-reports/edit'
import ExpenseClaimList from '../../components/expense-claims/list'

import statusMixin from '../../components/status-box/mixin'

import _ from 'lodash'
import { List } from 'immutable';
import { extractErrors } from '../../components/utils/api'

const Component = {

    props: ['sessionReportId'],

    mixins: [statusMixin],

    components: {
        'session-report-view': SessionReportView,
        'session-report-edit': SessionReportEdit,
        'expense-claim-list': ExpenseClaimList
    },

    template: `
        <div>
            <div class="card">
                <div class="card-header">
                    Session Report: {{ sessionReportId }}
                </div>
                <div class="card-body">
                    <div class="row edit-view-toggle" v-if="isInternalUser">
                        <div class="col-md mt-auto mb-auto text-left mode-selector">
                            <span :class="{'btn btn-primary btn-sm': true, 'disabled': false}" 
                                    type="button" 
                                    @click="switchMode('edit')">Edit</span>
                            <span :class="{'btn btn-primary btn-sm': true, 'disabled': false}" 
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
                        @sessionReportDeleted="initialiseAssociatedExpenseClaims">
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
                        :errors="errors">
                    </status-box>   
                    <expense-claim-list 
                        :expense-claims="associatedExpenseClaims" />
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
            this.initialiseAssociatedExpenseClaims()
        }
    },

    created() {
        this.initialiseAssociatedExpenseClaims()
    },

    mounted() {
    },

    methods: { 
        switchMode(mode) {
            this.mode = mode
        },
        async initialiseAssociatedExpenseClaims() {
            this.associatedExpenseClaims = List()
            try {
                this.associatedExpenseClaims = List(await this.fetchExpenseClaimsForSessionReport(this.sessionReportId))
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem getting associated expense claims`})
                this.addErrors({errs: messages})
            }
        },

        async fetchExpenseClaimsForSessionReport(sessionReportId) {
            return (await axios.get(`/api/expense-claims`, { params: {session_id: sessionReportId} } )).data
        }
    },
};

export default Component;