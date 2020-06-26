import _ from 'lodash'
import { parseDate, formatDate } from '../../utils/date'
import statusMixin from '../status-box/mixin'
import { mapActions } from 'vuex';

const Component = {

    props: {
        expenseClaimId: {},
        directLinkUrlResolver: {
            default: () => () => "unknown"
        }
    },

    mixins: [statusMixin],

    components: {
    },

    template: `
        <div class="expense-claim-view">     
            <status-box
                ref="status-box"
                class="status"
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses">
            </status-box>  

            <div v-if="expenseClaim">
                <div class="table-responsive section">
                    <p class="section-title">Claim Details</p>
                    <table class="table section-content expense-claim">
                        <col class="col-field">
                        <col class="col-value">
                        <tr>
                            <th>Field</th>
                            <th>Value</th>
                        </tr>
                        <tr class="claim-id">
                            <td class="label">Expense Claim ID</td>
                            <td class="value">{{ expenseClaim.id }}</td>
                        </tr>
                        <tr class="claim-link">
                            <td class="label">Direct Link</td>
                            <td class="value">
                                <span>
                                    <a :href="directLinkUrlResolver(expenseClaim.id)">Open</a> 
                                </span>
                            </td>
                        </tr>
                        <tr class="mentor-name" v-if="isInternalUser">
                            <td class="label">Mentor Name</td>
                            <td class="value">{{ expenseClaim.mentor.name }}</td>
                        </tr>
                        <tr class="status">
                            <td class="label">Status</td>
                            <td class="text-capitalize value">{{ expenseClaim.status }}</td>
                        </tr>
                        <tr class="finance-code" v-if="isInternalUser">
                            <td class="label">Finance Code</td>
                            <td class="value" v-if="expenseClaim.finance_code">{{ expenseClaim.finance_code }}</td>
                            <td class="value" v-else>None</td>
                        </tr> 
                    </table> 
                </div>

                <div class="table-responsive section">
                    <p class="section-title">Expense Items</p>
                    <table class="table section-content expense-items">
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                        <tr v-for="expenseItem in expenseClaim.expense_items">
                            <td class="expense-date">{{ displayableDate(expenseItem.date) }}</td>
                            <td class="expense-description">{{ expenseItem.description }}</td>
                            <td class="expense-amount">{{ expenseItem.amount }}</td>
                        </tr>
                    </table>
                </div>

                <div class="section">
                    <p class="section-title">Receipts</p>
                    <div class="table-responsive section-content" 
                        v-if="expenseClaim.receipts.length > 0">
                        <table class="table receipts">
                            <tr>
                                <th colspan="2">Click on the image(s) to download</th>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a 
                                        v-for="receipt in expenseClaim.receipts"
                                        class="receipt-link" 
                                        :href="'/receipts/' + receipt.id">
                                            <img class="preview-receipt" width="100" height="100" :src="'/receipts/' + receipt.id">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div v-else class="section-conent">
                        <p>No Receipts uploaded</p>
                    </div>
                </div>

                <div class="section" v-if="isInternalUser">
                    <p class="section-title">Processing</p>
                    <div v-if="expenseClaim.status !== 'pending'">
                        <p>This claim has been {{expenseClaim.status}} by {{expenseClaim.processing.by.name}}.</p>
                        <form v-if="isAdminUser" class="form-horizontal reverse-processing" @submit.prevent>
                            <!-- Revert Button -->
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <button class="btn btn-primary reverse-processing" 
                                            :disabled="isBusy" 
                                            data-toggle="modal" 
                                            data-target="#revert-confirmation">
                                        <span class="fas fa-history"></span> Revert To Pending
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="modal fade reverse-processing-confirm" 
                                id="revert-confirmation" 
                                tabindex="-1" role="dialog" 
                                aria-labelledby="revert processing" 
                                aria-hidden="true">
                            
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="confirmRevertTitle">Confirm revert</h3>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to revert processing of this expense claim?</p>

                                        <p>This will put the expense claim back into status <b>pending</b>, so that you can process/reject again.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button @click="revertProcessExpenseClaim" data-dismiss="modal" class="btn btn-primary reverse-processing-confirm">
                                            <span class="fas fa-history"></span>
                                            <span>Revert</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <form v-if="isAdminUser" class="form-horizontal processing" @submit.prevent>
                            <!-- Financial Number / Code -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label" for="codeInput">Finance Code (Optional)</label>
                                <div class="col-md-5">
                                    <input v-model="financeCode" 
                                            id="codeInput" 
                                            type="text" 
                                            class="form-control" 
                                            autofocus 
                                            :disabled="isBusy">
                                </div>
                            </div>
                            <!-- Process Button -->
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-3">
                                    <button @click="processExpenseClaim" 
                                            class="btn btn-primary process-expense-claim" 
                                            :disabled="isBusy" >
                                        <span class="fa fa-credit-card"></span> Process Payment
                                    </button>
                                    <button @click="rejectExpenseClaim" 
                                            class="btn btn-danger reject-expense-claim" 
                                            :disabled="isBusy" >
                                        <span class="fa fa-times"></span> Reject Expense Claim
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div v-else>
                            <p>This claim is awaiting processing by an administrator.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
            expenseClaim: null,

            // processing form
            isBusy: false,
            financeCode: null
        }
    },

    computed: {
        
    },

    watch: {
        expenseClaimId: function() { 
            this.clearStatus()
            this.tryInitialiseExpenseClaim() 
            this.financeCode = null
            this.isBusy = false
        }
    },

    created() {
        this.tryInitialiseExpenseClaim()
    },

    mounted() {
    },

    methods: { 
        displayableDate: dateString => formatDate(parseDate(dateString)),

        async tryInitialiseExpenseClaim() {
            if (!this.expenseClaimId) return
            this.expenseClaim = null
            this.try(`load expense claim (${this.expenseClaimId})`,
                async () => {
                    this.expenseClaim = await this.fetchExpenseClaim(this.expenseClaimId)
                    this.$emit('loaded', this.expenseClaim)
                }
            )
        },

        processExpenseClaim() {
            this.whilstBusy(() =>
                this.try(`process expense claim`,
                    async() => this.expenseClaim = await this.updateExpenseClaimStatus(
                        {id: this.expenseClaim.id, status: 'processed', financeCode: this.financeCode})
                )
            )
        },

        rejectExpenseClaim() {
            this.whilstBusy(() =>
                this.try(`reject expense claim`,
                    async() => this.expenseClaim = await this.updateExpenseClaimStatus(
                        {id: this.expenseClaim.id, status: 'rejected'})
                )
            )      
        },

        revertProcessExpenseClaim() {
            this.whilstBusy(() =>
                this.try(`revert processing of expense claim`,
                    async() => this.expenseClaim = await this.updateExpenseClaimStatus(
                        {id: this.expenseClaim.id, status: 'pending'})
                )
            )
        },

        ...mapActions('expenses', [
            'fetchExpenseClaim', 
            'updateExpenseClaimStatus'
        ]),

        async whilstBusy(func) {
            this.isBusy = true
            try {
                await func();
            } finally {
                this.isBusy = false
            }
        }
    }
};

export default Component;