import _ from 'lodash'
import { List } from 'immutable';

import { parseDate, formatDate } from '../../utils/date'
import Paginator from '../pagination/paginator'

const Component = {

    props: {
        expenseClaims: {
            default: () => List()
        },
        excludeFields: {
            default: () => []
        }
    },

    components: {
        Paginator
    },

    template: `
        <div class="expense-claim-list">    
            <paginator 
                :itemsToPaginate="expenseClaims" v-slot="{itemsToDisplay}"> 

                <div class="table-responsive">   
                    <table class="items table table-hover">
                        <thead>
                            <tr>
                                <th>Claim ID</th>
                                <th v-if="isInternalUser">Mentor Name</th>
                                <th>Created On</th>
                                <th>Status</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                v-for="claim in itemsToDisplay"
                                :id="'item-' + claim.id"
                                class="item"
                                @click="handleClickExpenseClaim(claim.id)">   

                                <td class="claim-id">{{claim.id}}</td>
                                <td v-if="isInternalUser" class="mentor-name">{{claim.mentor.name}}</td>
                                <td class="created-on">{{displayableDate(claim.created_at)}}</td>
                                <td class="status">{{claim.status}}</td>
                                <td class="total-amount">{{claim.amount_total}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </paginator>
            <div class="container mt-2">
                <span class="row">Total count: <span class="list-size">{{expenseClaims.size}}</span></span>
            </div>
        </div>
    `,

    data() {
        return {
            
        }
    },

    computed: {
       
    },

    watch: {
        expenseClaims: function(val) {
            // reset current page on new results
            this.currentPage = 1
        }
    },

    created() {},

    mounted() {},

    methods: { 
        displayableDate: dateString => formatDate(parseDate(dateString)),

        handleClickExpenseClaim(claimId) {
            this.$emit('claimSelected', claimId)
        }
    }
};

export default Component;