import _ from 'lodash'
import { parseDate, formatDate } from '../../utils/date'
import Paginator from '../pagination/paginator'

const Component = {

    props: {
        sessionReports: {
            default: () => List()
        }
    },

    components: {
        Paginator
    },

    template: `
        <div class="session-report-list">     
            <paginator 
                :itemsToPaginate="sessionReports" v-slot="{itemsToDisplay}">     
                <div class="table-responsive">   
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
                                v-for="sessionReport in itemsToDisplay"
                                :id="'item-' + sessionReport.id"
                                class="item"
                                @click="handleClickSessionReport(sessionReport.id)">   
                                <td class="session-id">{{sessionReport.id}}</td>
                                <td class="mentor-name">{{sessionReport.mentor.name}}</td>
                                <td class="mentee-name">{{sessionReport.mentee.name}}</td>
                                <td class="session-length">{{sessionReport.length_of_session}}</td>
                                <td class="session-date">{{displayableDate(sessionReport.session_date)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </paginator>
            <div class="container mt-2">
                <span class="row">Session report count: <span class="list-size">{{sessionReports.size}}</span></span>
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
        sessionReports: function(val) {
            // reset current page on new results
            this.currentPage = 1
        }    
    },

    created() {},

    mounted() {
    },

    methods: { 
        displayableDate: dateString => formatDate(parseDate(dateString)),

        handleClickSessionReport(sessionReportId) {
            this.$emit('sessionReportSelected', sessionReportId)
        }
    }
};

export default Component;