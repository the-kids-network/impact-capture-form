import _ from 'lodash'
import { extractErrors } from '../utils/api'
import { parseDate, formatDate } from '../utils/date'
import statusMixin from '../status-box/mixin'
import { SESSION_DATE_FORMAT } from './consts';

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

            <div class="container" v-if="sessionReport">
                <form class="form-horizontal" role="form">
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
                                :class="{ 'form-control': true, 'datepicker': true, 'sessiondate' : true, 'edited': isFieldDirty('sessionDate') }"
                                v-model="sessionDate"
                                autocomplete="off">
                            <div v-if="isFieldDirty('sessionDate')"
                                class="revertField"><span class="fas fa-history" @click="revertField('sessionDate')"/></div>
                        </div>
                    </div>

                    <!-- Session Rating -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="ratingInput">Session Rating</label>
                        <div class="col-md-6 entry">
                            <select id="ratingInput" 
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('ratingId') }"
                                    v-model="ratingId">
                                <option v-for="rating in sessionRatingsLookup"
                                    :value="rating.id">
                                    {{ rating.value }}
                                </option>
                            </select>
                            <div v-if="isFieldDirty('ratingId')"
                                class="revertField"><span class="fas fa-history" @click="revertField('ratingId')"/></div>
                        </div>
                    </div>

                    <!-- Length of Session -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="lengthInput">Length of Session (hours)</label>
                        <div class="col-md-6 entry">
                            <input id="lengthInput"
                                type="text" 
                                :class="{ 'form-control': true, 'edited': isFieldDirty('lengthOfSession') }"
                                v-model="lengthOfSession">
                            <div v-if="isFieldDirty('lengthOfSession')"
                                class="revertField"><span class="fas fa-history" @click="revertField('lengthOfSession')"/></div>
                        </div>
                    </div>

                    <!-- Type of Activity -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="activityTypeInput">Activity Type</label>
                        <div class="col-md-6 entry">
                            <select id="activityTypeInput"
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('activityTypeId') }"
                                    v-model="activityTypeId">
                                <option v-for="activityType in activityTypesLookup"
                                    :value="activityType.id">
                                    {{ activityType.name }}
                                </option>
                            </select>
                            <div v-if="isFieldDirty('activityTypeId')"
                                class="revertField"><span class="fas fa-history" @click="revertField('activityTypeId')"/></div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="locationInput">Location</label>

                        <div class="col-md-6 entry">
                            <input id="locationInput"
                                type="text" 
                                :class="{ 'form-control': true, 'edited': isFieldDirty('location') }"
                                v-model="location">
                            <div v-if="isFieldDirty('location')"
                                class="revertField"><span class="fas fa-history" @click="revertField('location')"/></div>
                        </div>
                    </div>

                    <!-- Safeguarding Concern -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="safeguardingInput">Safeguarding Concern</label>

                        <div class="col-md-6 entry">
                            <select id="safeguardingInput"
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('safeguardingConcern') }"
                                    v-model="safeguardingConcern">
                                <option v-for="item in safeguardingLookup"
                                        :value="item.id">
                                        {{ item.name }}
                                </option>
                            </select>
                            <div v-if="isFieldDirty('safeguardingConcern')"
                                class="revertField"><span class="fas fa-history" @click="revertField('safeguardingConcern')"/></div>
                        </div>
                    </div>

                    <!-- Emotional State -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="emotionalStateInput">Mentee's Emotional State</label>
                        <div class="col-md-6 entry">
                            <select id="emotionalStateInput"
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('emotionalStateId') }"
                                    v-model="emotionalStateId">
                                <option v-for="emotionalState in emotionalStatesLookup"
                                    :value="emotionalState.id">
                                    {{ emotionalState.name }}
                                </option>
                            </select>
                            <div v-if="isFieldDirty('emotionalStateId')"
                                class="revertField"><span class="fas fa-history" @click="revertField('emotionalStateId')"/></div>
                        </div>
                    </div>

                    <!-- Meeting Details -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="meetingDetailsInput">Meeting Details</label>
                        <div class="col-md-6 entry">
                            <textarea id="meetingDetailsInput"
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('meetingDetails') }"
                                    rows="10"
                                    v-model="meetingDetails"/>
                            <div v-if="isFieldDirty('meetingDetails')"
                                class="revertField"><span class="fas fa-history" @click="revertField('meetingDetails')"/></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md offset-md-4">
                            <span @click="handleSaveSessionReport" class="save btn btn-success " :disabled="isBusy">
                            <span class="fas fa-save" /> Save</span>
                            <span class="btn btn-danger" data-toggle="modal" data-target="#delete-confirmation" :disabled="isBusy">
                            <span class="fas fa-times" /> Delete</span>
                        </div>
                    </div>

                    <div class="modal fade" id="delete-confirmation" tabindex="-1" role="dialog" aria-labelledby="delete report" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title" id="exampleModalLabel">Confirm deletion of report</h3>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete the session report?</p>

                                    <p>This will <b>delete all associated expense claims</b>, so make sure they have not been processed / paid already.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                                    <button @click="handleDeleteSessionReport" data-dismiss="modal" class="btn btn-danger">
                                        <span class="fas fa-times"></span>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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

            // disabled elements
            isBusy: false,
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
            this.intialiseSessionReport()
        },
        sessionReport: function() {
            Object.assign(this.$data, this.buildState(this.sessionReport));          
        }
    },

    async created() {
        this.intialiseSessionReport()
        this.initialiseActivityTypesLookup()
        this.initialiseEmotionalStatesLookup()
        this.initialiseSessionRatingsLookup()
        this.initialiseSafeguardingConcernLookup()
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
        isFieldDirty(editableFieldName) {
            return this._originalState[editableFieldName] !== this[editableFieldName]
        },

        revertField(editableFieldName) {
            if (this.hasOwnProperty(editableFieldName)) {
                this[editableFieldName] = this._originalState[editableFieldName]
            }
        },

        async initialiseActivityTypesLookup() {
            try {
                this.activityTypesLookup = await this.fetchActivityTypes()
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem loading activity types lookup`})
                this.addErrors({errs: messages})
            }
        },

        async initialiseEmotionalStatesLookup() {
            try {
                this.emotionalStatesLookup = await this.fetchEmotionalStates()
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem loading emotional states lookup`})
                this.addErrors({errs: messages})
            }
        },

        async initialiseSessionRatingsLookup() {
            try {
                this.sessionRatingsLookup = await this.fetchSessionRatings()
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem loading session ratings lookup`})
                this.addErrors({errs: messages})
            }
        },

        async initialiseSafeguardingConcernLookup() {
            try {
                this.safeguardingLookup = await this.fetchSafeguardingOptions()
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem loading safeguarding lookup`})
                this.addErrors({errs: messages})
            }
        },

        async intialiseSessionReport() {
            if (!this.sessionReportId) return

            this.sessionReport = null
            try {
                this.sessionReport = await this.fetchSessionReport(this.sessionReportId)
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem loading session report (${this.sessionReportId})`})
                this.addErrors({errs: messages})
            }
        },

        async handleSaveSessionReport() {
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
                this.isBusy = true
                this.sessionReport = await this.updateSessionReport(this.sessionReport.id, reportBody)
                this.addSuccesses({succs: ['Report was saved successfully']})
                this.$emit('sessionReportSaved')
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: 'Problem saving the session report'})
                this.addErrors({errs: messages})
            } finally {
                this.isBusy = false
            }
        },

        async handleDeleteSessionReport() {
            this.clearStatus()
            try {
                this.isBusy = true
                await this.deleteSessionReport(this.sessionReport.id)
                this.sessionReport = null
                this.addSuccesses({succs: ['Report was deleted successfully']})
                this.$emit('sessionReportDeleted')
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: 'Problem deleting the session report'})
                this.addErrors({errs: messages})
            } finally {
                this.isBusy = false
            }
        },

        /**
         * Functions that do not interact with Vue state
         */
        async deleteSessionReport(id) {
            return (await axios.delete(`/api/session-reports/${id}`)).data
        },

        async updateSessionReport(id, reportData) {
            return (await axios.put(`/api/session-reports/${id}`, reportData)).data
        },

        async fetchSessionReport(id) {
            return (await axios.get(`/api/session-reports/${id}`)).data
        },

        async fetchActivityTypes() {
            return (await axios.get('/api/activity-types', { params: {trashed: true} })).data
        },

        async fetchEmotionalStates() {
            return (await axios.get('/api/emotional-states', { params: {trashed: true} })).data
        },

        async fetchSessionRatings() {
            return (await axios.get('/api/session-ratings')).data
        },

        async fetchSafeguardingOptions() {
            return (await axios.get('/api/safeguarding-options')).data
        },

        buildState : sessionReport =>  ( 
            sessionReport ?
                {
                    sessionDate: formatDate(parseDate(sessionReport.session_date), SESSION_DATE_FORMAT),
                    ratingId: sessionReport.rating.id,
                    lengthOfSession: sessionReport.length_of_session.toString(),
                    activityTypeId: sessionReport.activity_type.id,
                    location: sessionReport.location,
                    safeguardingConcern: sessionReport.safeguarding_concern.id,
                    emotionalStateId: sessionReport.emotional_state.id,
                    meetingDetails: sessionReport.meeting_details
                }
                : 
                {
                    sessionDate: null,
                    ratingId: null,
                    lengthOfSession: null,
                    activityTypeId: null,
                    location: null,
                    safeguardingConcern: null,
                    emotionalStateId: null,
                    meetingDetails: null
                }
        )
    }
};

export default Component;