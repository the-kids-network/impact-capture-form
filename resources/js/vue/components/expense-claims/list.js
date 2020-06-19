import _ from 'lodash'
import { List } from 'immutable';

import { parseDate, formatDate } from '../../utils/date'
import Paginator from '../pagination/paginator'

const Component = {

    props: {
        expenseClaims: {
            default: () => List()
        }
    },

    components: {
        Paginator
    },

    template: `
        <div class="expense-claims-list">    
            <paginator 
                :itemsToPaginate="expenseClaims" v-slot="{itemsToDisplay}"> 

                <div class="table-responsive">   
                    <table class="items table table-hover">
                        <thead>
                            <tr>
                                <th>Claim ID</th>
                                <th>Created On</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr 
                                v-for="claim in itemsToDisplay"
                                :id="'item-' + claim.id"
                                class="item">   

                                <td class="claim-id">{{claim.id}}</td>
                                <td class="created-on">{{displayableDate(claim.created_at)}}</td>
                                <td class="status">{{claim.status}}</td>
                                <td class="total-amount">{{claim.amount_total}}</td>
                                <td class="link"><a :href="'/expense-claim/' + claim.id">Link</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </paginator>
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
    }
};

export default Component;