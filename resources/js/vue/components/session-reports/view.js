import _ from 'lodash'
import { formatDate as fd } from '../utils/date'
import { extractErrors } from '../utils/api'
import statusMixin from '../status-box/mixin'

const Component = {

    props: ['sessionReportId'],

    mixins: [statusMixin],

    components: {
    },

    template: `
        <div class="session-report-viewer">     
            <status-box
                ref="status-box"
                class="status"
                :errors="errors">
            </status-box>  

            <div class="table-responsive" v-if="sessionReport">
                <table class="table">
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                    <tr class="session-id">
                        <td class="label">Session ID</td>
                        <td class="value">{{ sessionReport.id }}</td>
                    </tr>
                    <tr class="mentor-name">
                        <td class="label">Mentor Name</td>
                        <td class="value">{{ sessionReport.mentor.name }}</td>
                    </tr>
                    <tr class="mentee-name">
                        <td class="label">Mentee Name</td>
                        <td class="value">{{ sessionReport.mentee.name }}</td>
                    </tr>
                    <tr class="session-date">
                        <td class="label">Session Date</td>
                        <td class="value">{{ formatDate(sessionReport.session_date) }}</td>
                    </tr>
                    <tr class="session-rating">
                        <td class="label">Session Rating</td>
                        <td class="value">{{ sessionReport.rating.value }}</td>
                    </tr>
                    <tr class="session-length">
                        <td class="label">Session Length (Hours)</td>
                        <td class="value">{{ sessionReport.length_of_session }}</td>
                    </tr>
                    <tr class="activity-type">
                        <td class="label">Activity Type</td>
                        <td class="value">{{ sessionReport.activity_type.name }}</td>
                    </tr>
                    <tr class="session-location">
                        <td class="label">Location</td>
                        <td class="value">{{ sessionReport.location }}</td>
                    </tr>
                    <tr class="safeguarding-concern">
                        <td class="label">Safeguarding Concern</td>
                        <td class="value" v-if="sessionReport.safeguarding_concern.id > 0">
                            Yes - {{sessionReport.safeguarding_concern.type}}
                        </td>
                        <td v-else>
                            No
                        </td>
                    </tr>
                    <tr class="mentee-emotional-state">
                        <td class="label">Mentee's Emotional State</td>
                        <td class="value">{{ sessionReport.emotional_state.name }}</td>
                    </tr>
                    <tr class="meeting-details">
                        <td class="label">Meeting Details</td>
                        <td class="value">{{ sessionReport.meeting_details }}</td>
                    </tr>
                </table> 
            </div>
        </div>
    `,

    data() {
        return {
            sessionReport: null
        }
    },

    computed: {
        
    },

    watch: {
        sessionReportId: function() { 
            this.clearErrors()
            this.setSessionReport() 
        }
    },

    created() {
        this.setSessionReport()
    },

    mounted() {
        
    },

    methods: { 
        formatDate: fd,

        async setSessionReport() {
            try {
                this.sessionReport = await this.getSessionReport(this.sessionReportId)
            } catch (e) {
                this.addErrors(extractErrors({e, defaultMsg: `Unknown problem loading session report: ${this.sessionReportId}`}))
            }
        },

        async getSessionReport(id) {
            return (await axios.get(`/session-reports/${id}`)).data
        }
    }
};

export default Component;