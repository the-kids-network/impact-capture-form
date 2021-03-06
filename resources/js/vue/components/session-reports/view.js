import _ from 'lodash'
import Popper from 'vue-popperjs';
import { parseDate, formatDate } from '../../utils/date'
import statusMixin from '../status-box/mixin'
import { mapActions } from 'vuex';

const Component = {

    props: ['sessionReportId'],

    mixins: [statusMixin],

    components: {
        'popper': Popper
    },

    template: `
        <div class="session-report-view">     
            <status-box
                ref="status-box"
                class="status"
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses">
            </status-box>  

            <div class="table-responsive" v-if="sessionReport">
                <table class="table session-report">
                    <col class="col-field">
	                <col class="col-value">
                    <tr>
                        <th>Field</th>
                        <th>Value</th>
                    </tr>
                    <tr class="session-id">
                        <td class="label">Session ID</td>
                        <td class="value">
                            {{ sessionReport.id }}
                        </td>
                    </tr>
                    <tr class="session-link">
                        <td class="label">Direct Link</td>
                        <td class="value">
                            <span>
                                <a :href="getSessionReportHref(sessionReport)">Open</a> 
                            </span>
                        </td>
                    </tr>
                    <tr class="mentor-name" v-if="isInternalUser">
                        <td class="label">Mentor Name</td>
                        <td class="value">{{ sessionReport.mentor.name }}</td>
                    </tr>
                    <tr class="mentee-name">
                        <td class="label">Mentee Name</td>
                        <td class="value">{{ sessionReport.mentee.name }}</td>
                    </tr>
                    <tr class="session-date">
                        <td class="label">Session Date</td>
                        <td class="value">{{ displayableDate(sessionReport.session_date) }}</td>
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
                        <td class="value">
                            {{sessionReport.safeguarding_concern.label}}
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
            popover: {
                trigger: 'hover',
                delayOnMouseOver: 0,
                options: {
                    placement: 'top',
                }
            },

            sessionReport: null
        }
    },

    computed: {
        
    },

    watch: {
        sessionReportId: function() { 
            this.clearStatus()
            this.initialiseSessionReport() 
        }
    },

    created() {
        this.initialiseSessionReport()
    },

    mounted() {
    },

    methods: { 
        displayableDate: dateString => formatDate(parseDate(dateString)),

        async initialiseSessionReport() {
            if (!this.sessionReportId) return
            this.sessionReport = null
            this.try(`load session report (${this.sessionReportId})`,
                async () => this.sessionReport = await this.fetchSessionReport(this.sessionReportId)
            )
        },

        getSessionReportHref(sessionReport) {
            let props = this.$router.resolve({ 
                name: 'session-reports-view-one',
                params: { sessionReportId: sessionReport.id },
            });
            return props.href;
        },

        ...mapActions('sessionReports', ['fetchSessionReport'])
    }
};

export default Component;