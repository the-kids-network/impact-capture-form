import _ from 'lodash'
import { formatDate as fd } from '../utils/date'
import { range } from '../utils/number'
import { numberOfPages, itemsForPage } from '../utils/pagination'

const Component = {

    props: {
        sessionReports: {
            default: () => []        
        }
    },

    components: {
    },

    template: `
        <div class="session-report-list">            
            <table class="items table table-hover">
                <thead>
                    <tr>
                        <th>Session ID</th>
                        <th>Mentor Name</th>
                        <th>Mentee Name</th>
                        <th>Session Length</th>
                        <th>Session Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="sessionReport in _itemsForCurrentPage"
                        :id="'item-' + sessionReport.id"
                        class="item"
                        @click="sessionReportClicked(sessionReport.id)">   

                        <td class="session-id">
                            {{sessionReport.id}}
                        </td>
                        <td class="mentor-name">
                            {{sessionReport.mentor.name}}
                        </td>
                        <td class="mentee-name">
                            {{sessionReport.mentee.name}}
                        </td>
                        <td class="session-length">
                            {{sessionReport.length_of_session}}
                        </td>
                        <td class="session-date">
                            {{formatDate(sessionReport.session_date)}}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination-bar">
                <div class="page-size-list">
                    <span class="btn-group dropdown dropup">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                            <span class="page-size">{{currentPageSize}}</span>
                        </button>
                        <div class="dropdown-menu" role="menu">
                            <a  v-for="size in pageSizes"
                                :class="'dropdown-item page-size ' + ((size === currentPageSize) ? 'active' : '')" 
                                @click="currentPageSize = size"
                                role="menuitem"
                                href="#">{{size}}</a>
                        </div>
                    </span> rows per page
                </div>
                <div class="page-selector" v-if="_pages.length > 1">
                    <ul class="pagination pages-list justify-content-end">
                        <li class="page-item" 
                            v-if="currentPage != 1" 
                            @click="currentPage--">
                            <a class="page-link"  href="#"> &lt; </a>
                        </li>
                        <li :class="'page-item ' + ((page === currentPage) ? 'active' : '')" 
                            v-for="page in _pages" 
                            @click="currentPage = page">
                            <a class="page-link"  href="#"> {{page}} </a>
                        </li>
                        <li class="page-item" 
                            @click="currentPage++" 
                            v-if="currentPage < _pages.length">
                            <a class="page-link" href="#"> &gt; </a>
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
            currentPageSize: 2
        }
    },

    computed: {
        _itemsForCurrentPage() {
            return itemsForPage(this.sessionReports, this.currentPage, this.currentPageSize)
       },
       _pages() {
           const n = numberOfPages(this.sessionReports, this.currentPageSize);
           return range(1, n);
       },
    },

    watch: {},

    created() {},

    mounted() {},

    methods: { 
        formatDate: fd,

        sessionReportClicked(sessionReportId) {
            this.$emit('sessionReportSelected', sessionReportId)
        }
    }
};



export default Component;