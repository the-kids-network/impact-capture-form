import _ from 'lodash'
import { extractErrors } from '../utils/api'
import { formatDate as df } from '../utils/date'
import statusMixin from '../status-box/mixin'

const Component = {

    props: ['sessionReportId'],

    mixins: [statusMixin],

    components: {
    },

    template: `
        <div class="session-report-edit">            
            <status-box
                ref="status-box"
                class="status"
                :successes="successes"
                :errors="errors">
            </status-box>   

            <form v-if="sessionReport" class="form-horizontal" role="form">
                <!-- Mentee's Name -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="menteeInput">Mentee</label>
                    <div class="col-md-6">
                        <select id="menteeInput" class="form-control" name="mentee_id" disabled>
                            <option
                                :value="sessionReport.mentee.id" selected>
                                {{ sessionReport.mentee.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Date of Session -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="sessionDateInput">Session Date</label>
                    <div class="col-md-6 entry">
                        <input id="sessionDateInput"
                               type="text" 
                               :class="{ 'form-control': true, 'datepicker': true, 'sessiondate' : true, 'edited': isDirty('sessionDate') }"
                               v-model="sessionDate"
                               autocomplete="off">
                        <div v-if="isDirty('sessionDate')"
                               class="revert"><span class="fas fa-history" @click="revert('sessionDate')"/></div>
                    </div>
                </div>

                <!-- Session Rating -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="ratingInput">Session Rating</label>
                    <div class="col-md-6 entry">
                        <select id="ratingInput" 
                                :class="{ 'form-control': true, 'edited': isDirty('ratingId') }"
                                v-model="ratingId">
                            <option v-for="rating in sessionRatingsLookup"
                                :value="rating.id">
                                {{ rating.value }}
                            </option>
                        </select>
                        <div v-if="isDirty('ratingId')"
                             class="revert"><span class="fas fa-history" @click="revert('ratingId')"/></div>
                    </div>
                </div>

                <!-- Length of Session -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="lengthInput">Length of Session (hours)</label>
                    <div class="col-md-6 entry">
                        <input id="lengthInput"
                               type="text" 
                               :class="{ 'form-control': true, 'edited': isDirty('lengthOfSession') }"
                               v-model="lengthOfSession">
                        <div v-if="isDirty('lengthOfSession')"
                             class="revert"><span class="fas fa-history" @click="revert('lengthOfSession')"/></div>
                    </div>
                </div>

                <!-- Type of Activity -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="activityTypeInput">Activity Type</label>
                    <div class="col-md-6 entry">
                        <select id="activityTypeInput"
                                :class="{ 'form-control': true, 'edited': isDirty('activityTypeId') }"
                                v-model="activityTypeId">
                            <option v-for="activityType in activityTypesLookup"
                                :value="activityType.id">
                                {{ activityType.name }}
                            </option>
                        </select>
                        <div v-if="isDirty('activityTypeId')"
                             class="revert"><span class="fas fa-history" @click="revert('activityTypeId')"/></div>
                    </div>
                </div>

                <!-- Location -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="locationInput">Location</label>

                    <div class="col-md-6 entry">
                        <input id="locationInput"
                               type="text" 
                               :class="{ 'form-control': true, 'edited': isDirty('location') }"
                               v-model="location">
                        <div v-if="isDirty('location')"
                             class="revert"><span class="fas fa-history" @click="revert('location')"/></div>
                    </div>
                </div>

                <!-- Safeguarding Concern -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="safeguardingInput">Safeguarding Concern</label>

                    <div class="col-md-6 entry">
                        <select id="safeguardingInput"
                                :class="{ 'form-control': true, 'edited': isDirty('safeguardingConcern') }"
                                v-model="safeguardingConcern">
                            <option v-for="item in safeguardingLookup"
                                    :value="item.id">
                                    {{ item.name }}
                            </option>
                        </select>
                        <div v-if="isDirty('safeguardingConcern')"
                             class="revert"><span class="fas fa-history" @click="revert('safeguardingConcern')"/></div>
                    </div>
                </div>

                <!-- Emotional State -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="emotionalStateInput">Mentee's Emotional State</label>
                    <div class="col-md-6 entry">
                        <select id="emotionalStateInput"
                                :class="{ 'form-control': true, 'edited': isDirty('emotionalStateId') }"
                                v-model="emotionalStateId">
                            <option v-for="emotionalState in emotionalStatesLookup"
                                :value="emotionalState.id">
                                {{ emotionalState.name }}
                            </option>
                        </select>
                        <div v-if="isDirty('emotionalStateId')"
                            class="revert"><span class="fas fa-history" @click="revert('emotionalStateId')"/></div>
                    </div>
                </div>

                <!-- Meeting Details -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="meetingDetailsInput">Meeting Details</label>
                    <div class="col-md-6 entry">
                        <textarea id="meetingDetailsInput"
                                  :class="{ 'form-control': true, 'edited': isDirty('meetingDetails') }"
                                  rows="10"
                                  v-model="meetingDetails"/>
                        <div v-if="isDirty('meetingDetails')"
                            class="revert"><span class="fas fa-history" @click="revert('meetingDetails')"/></div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <span v-on:click="saveSessionReport()" class="save btn btn-success " :disabled="isSaving">
                        <span class="fas fa-save" /> Save</span>
                    </div>
                </div>
            </form>
        </div>
    `,

    data() {
        return {
            // lookups
            activityTypesLookup: [],
            emotionalStatesLookup: [],
            sessionRatingsLookup: [],
            safeguardingLookup: [],

            // original session report
            sessionReport: null,

            // editable (current unsaved) state
            sessionDate: null,
            ratingId: null,
            lengthOfSession: null,
            activityTypeId: null,
            location: null,
            safeguardingConcern: null,
            emotionalStateId: null,
            meetingDetails: null,

            // saving
            isSaving: false,
        }
    },

    computed: {
        _originalState() {
            return this.buildState(this.sessionReport)            
        }
    },

    watch: {
        sessionReportId: function() {
            this.clearStatus()
            this.setSessionReport()
        },
        sessionReport: function() {
            Object.assign(this.$data, this.buildState(this.sessionReport));          
        }
    },

    async created() {
        this.setSessionReport()
        this.setActivityTypesLookup()
        this.setEmotionalStatesLookup()
        this.setSessionRatingsLookup()
        this.setSafeguardingConcernLookup()
    },

    mounted() {
    },

    updated() {
        const vm = this;
        $( ".datepicker.sessiondate" ).datepicker({ 
            dateFormat: 'dd-mm-yy', 
            onSelect: function(dateText) {
                vm.sessionDate = dateText
            }
        });
    },

    methods: { 
        isDirty(editableFieldName) {
            return this._originalState[editableFieldName] !== this[editableFieldName]
        },

        revert(editableFieldName) {
            if (this.hasOwnProperty(editableFieldName)) {
                this[editableFieldName] = this._originalState[editableFieldName]
            }
        },

        async setActivityTypesLookup() {
            try {
                this.activityTypesLookup = await this.getActivityTypes()
            } catch (e) {
                this.addErrors(extractErrors({e, defaultMsg: `Unknown problem loading activity types lookup`}))
            }
        },

        async setEmotionalStatesLookup() {
            try {
                this.emotionalStatesLookup = await this.getEmotionalStates()
            } catch (e) {
                this.addErrors(extractErrors({e, defaultMsg: `Unknown problem loading emotional states lookup`}))
            }
        },

        async setSessionRatingsLookup() {
            try {
                this.sessionRatingsLookup = await this.getSessionRatings()
            } catch (e) {
                this.addErrors(extractErrors({e, defaultMsg: `Unknown problem loading session ratings lookup`}))
            }
        },

        async setSafeguardingConcernLookup() {
            try {
                this.safeguardingLookup = await this.getSafeguardingOptions()
            } catch (e) {
                this.addErrors(extractErrors({e, defaultMsg: `Unknown problem loading safeguarding lookup`}))
            }
        },

        async setSessionReport() {
            try {
                this.sessionReport = await this.getSessionReport(this.sessionReportId)
            } catch (e) {
                this.addErrors(extractErrors({e, defaultMsg: `Unknown problem loading session report: ${this.sessionReportId}`}))
            }
        },

        async saveSessionReport() {
            this.clearStatus()

            const reportBody = {
                mentor_id: this.sessionReport.mentor.id,
                mentee_id: this.sessionReport.mentee.id,
                // editable properties
                session_date: this.sessionDate,
                rating_id: this.ratingId,
                length_of_session: this.lengthOfSession,
                activity_type_id: this.activityTypeId,
                location: this.location,
                safeguarding_concern: this.safeguardingConcern,
                emotional_state_id: this.emotionalStateId,
                meeting_details: this.meetingDetails
            }

            try {
                this.isSaving = true
                this.sessionReport = await this.updateSessionReport(this.sessionReport.id, reportBody)
                this.addSuccesses(['Report was saved successfully'])
            } catch (e) {
                this.addErrors(extractErrors({e, defaultMsg: 'Unknown problem saving the session report'}))
            } finally {
                this.isSaving = false
            }
        },

        /**
         * Functions that do not interact with Vue state
         */
        async updateSessionReport(id, reportData) {
            return (await axios.put(`/session-reports/${id}`, reportData)).data
        },

        async getSessionReport(id) {
            return (await axios.get(`/session-reports/${id}`)).data
        },

        async getActivityTypes() {
            return (await axios.get('/activity-types', { params: {trashed: true} })).data
        },

        async getEmotionalStates() {
            return (await axios.get('/emotional-states', { params: {trashed: true} })).data
        },

        async getSessionRatings() {
            return (await axios.get('/session-ratings')).data
        },

        async getSafeguardingOptions() {
            return (await axios.get('/safeguarding-options')).data
        },

        buildState : (sessionReport) =>  ( 
            {
                sessionDate: df(sessionReport.session_date,  'DD-MM-YYYY'),
                ratingId: sessionReport.rating.id,
                lengthOfSession: sessionReport.length_of_session.toString(),
                activityTypeId: sessionReport.activity_type.id,
                location: sessionReport.location,
                safeguardingConcern: sessionReport.safeguarding_concern.id,
                emotionalStateId: sessionReport.emotional_state.id,
                meetingDetails: sessionReport.meeting_details
            }
        )
    }
};

export default Component;