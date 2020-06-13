import _ from 'lodash'
import { parseDate, formatDate } from '../../utils/date'
import { range } from '../../utils/number'
import { numberOfPages, itemsForPage } from '../../utils/pagination'

const Component = {

    props: {
        expenseClaims: {
            default: () => List()
        }
    },

    components: {
    },

    template: `
        <div class="expense-claims-list">         
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
                            v-for="claim in itemsForCurrentPage"
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

            <div class="pagination-bar">
                <div class="page-size-list">
                    <span class="btn-group dropdown dropup">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                            <span class="page-size">{{currentPageSize}}</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a  v-for="size in pageSizes"
                                :class="'dropdown-item page-size ' + ((size === currentPageSize) ? 'active' : '')" 
                                @click.prevent="currentPageSize = size"
                                role="menuitem"
                                href="#">{{size}}</a>
                        </div>
                    </span> rows per page
                </div>
                <div class="page-selector" v-if="pages.length > 1">
                    <ul class="pagination pages-list justify-content-end">
                        <li class="page-item" 
                            v-if="currentPage != 1" 
                            @click="currentPage--">
                            <a @click.prevent class="page-link"  href="#"> &lt; </a>
                        </li>
                        <li :class="'page-item ' + ((page === currentPage) ? 'active' : '')" 
                            v-for="page in pages" 
                            @click="currentPage = page">
                            <a @click.prevent class="page-link"  href="#"> {{page}} </a>
                        </li>
                        <li class="page-item" 
                            @click="currentPage++" 
                            v-if="currentPage < pages.length">
                            <a @click.prevent class="page-link" href="#"> &gt; </a>
                        </li>
                    </ul>
                </div>	
            </div>
        </div>
    `,

    data() {
        return {
            // pagination state
            currentPage: 1,
            pageSizes: [10, 25, 50, 100],
            currentPageSize: 10
        }
    },

    computed: {
        itemsForCurrentPage() {
            return itemsForPage(this.expenseClaims, this.currentPage, this.currentPageSize)
        },
        pages() {
           const n = numberOfPages(this.expenseClaims.size, this.currentPageSize);
           return range(1, n);
        },
    },

    watch: {
        expenseClaims: function(val) {
            // reset current page on new results
            this.currentPage = 1
        }
    },

    created() {},

    mounted() {
    },

    methods: { 
        displayableDate: dateString => formatDate(parseDate(dateString)),
    }
};

export default Component;