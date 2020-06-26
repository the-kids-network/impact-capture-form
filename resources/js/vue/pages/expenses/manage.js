
import _ from 'lodash'
import { mapActions } from 'vuex';
import { List } from 'immutable';

import ExpenseClaimView from '../../components/expenses/view'
import SessionReportList from '../../components/session-reports/list'
import statusMixin from '../../components/status-box/mixin'

const Component = {

    props: ['expenseClaimId'],

    mixins: [statusMixin],

    components: {
        'expense-claim-view': ExpenseClaimView,
        'session-report-list': SessionReportList
    },

    template: `
        <div class="expense-claim-manage">
            <status-box
                ref="status-box"
                class="status"
                :successes="successes"
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses">
            </status-box>
            <div class="card">
                <div class="card-header">
                    Expense Claim: {{ expenseClaimId }}
                </div>
                <div class="card-body">
                    <expense-claim-view
                        :expense-claim-id="expenseClaimId"
                        :directLinkUrlResolver="directLinkUrlForClaim"
                        @loaded="tryInitialiseAssociatedSessionReports">
                    </expense-claim-view>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Associated Session
                </div>
                <div class="card-body">
                    <session-report-list 
                        :session-reports="associatedSessionReports"
                        @sessionReportSelected="goToSessionReport" />
                </div>
            </div>
        </div>
    `,

    data() {
        return {
            associatedSessionReports: List()
        }
    },

    computed: {
    },

    watch: {
        expenseClaimId() {
            this.associatedSessionReports = List()
        }
    },

    created() {
    },

    mounted() {
    },

    methods: { 
        async tryInitialiseAssociatedSessionReports(expenseClaim) {
            this.associatedSessionReports = List()
            this.try("get associated session report", 
                async () => this.associatedSessionReports = List.of(await this.fetchSessionReport(expenseClaim.session.id))
            )
        },

        directLinkUrlForClaim(id) {
            let props = this.$router.resolve({ 
                name: 'expense-claims-view-one',
                params: { expenseClaimId: id },
            });
            return props.href;
        },

        goToSessionReport(id) {
            this.$router.push({ name: 'session-reports-view-one', params: { 'sessionReportId': id } })
        },

        ...mapActions('sessionReports', ['fetchSessionReport'])
    },
};

export default Component;