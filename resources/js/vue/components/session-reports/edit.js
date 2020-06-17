import _ from 'lodash'
import { parseDate, formatDate } from '../../utils/date'
import statusMixin from '../status-box/mixin'
import { SESSION_DATE_FORMAT } from './consts';
import { mapActions, mapState } from 'vuex';

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
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses">
            </status-box>   

            <div class="session-report-edit-form container" v-if="sessionReport">
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
                                :class="{ 'form-control': true, 'datepicker': true, 'sessiondate' : true, 'edited': isFieldDirty('f_sessionDate') }"
                                v-model="f_sessionDate"
                                autocomplete="off">
                            <div v-if="isFieldDirty('f_sessionDate')"
                                class="revertField"><span class="fas fa-history" @click="revertField('f_sessionDate')"/></div>
                        </div>
                    </div>

                    <!-- Session Rating -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="ratingInput">Session Rating</label>
                        <div class="col-md-6 entry">
                            <select id="ratingInput" 
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('f_ratingId') }"
                                    v-model="f_ratingId">
                                <option v-for="rating in sessionRatingsLookup"
                                    :value="rating.id">
                                    {{ rating.value }}
                                </option>
                            </select>
                            <div v-if="isFieldDirty('f_ratingId')"
                                class="revertField"><span class="fas fa-history" @click="revertField('f_ratingId')"/></div>
                        </div>
                    </div>

                    <!-- Length of Session -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="lengthInput">Length of Session (hours)</label>
                        <div class="col-md-6 entry">
                            <input id="lengthInput"
                                type="text" 
                                :class="{ 'form-control': true, 'edited': isFieldDirty('f_lengthOfSession') }"
                                v-model="f_lengthOfSession">
                            <div v-if="isFieldDirty('f_lengthOfSession')"
                                class="revertField"><span class="fas fa-history" @click="revertField('f_lengthOfSession')"/></div>
                        </div>
                    </div>

                    <!-- Type of Activity -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="activityTypeInput">Activity Type</label>
                        <div class="col-md-6 entry">
                            <select id="activityTypeInput"
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('f_activityTypeId') }"
                                    v-model="f_activityTypeId">
                                <option v-for="activityType in activityTypesLookup"
                                    :value="activityType.id">
                                    {{ activityType.name }}
                                </option>
                            </select>
                            <div v-if="isFieldDirty('f_activityTypeId')"
                                class="revertField"><span class="fas fa-history" @click="revertField('f_activityTypeId')"/></div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="locationInput">Location</label>

                        <div class="col-md-6 entry">
                            <input id="locationInput"
                                type="text" 
                                :class="{ 'form-control': true, 'edited': isFieldDirty('f_location') }"
                                v-model="f_location">
                            <div v-if="isFieldDirty('f_location')"
                                class="revertField"><span class="fas fa-history" @click="revertField('f_location')"/></div>
                        </div>
                    </div>

                    <!-- Safeguarding Concern -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="safeguardingInput">Safeguarding Concern</label>

                        <div class="col-md-6 entry">
                            <select id="safeguardingInput"
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('f_safeguardingConcernId') }"
                                    v-model="f_safeguardingConcernId">
                                <option v-for="item in safeguardingLookup"
                                        :value="item.id">
                                        {{ item.label }}
                                </option>
                            </select>
                            <div v-if="isFieldDirty('f_safeguardingConcernId')"
                                class="revertField"><span class="fas fa-history" @click="revertField('f_safeguardingConcernId')"/></div>
                        </div>
                    </div>

                    <!-- Emotional State -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="emotionalStateInput">Mentee's Emotional State</label>
                        <div class="col-md-6 entry">
                            <select id="emotionalStateInput"
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('f_emotionalStateId') }"
                                    v-model="f_emotionalStateId">
                                <option v-for="emotionalState in emotionalStatesLookup"
                                    :value="emotionalState.id">
                                    {{ emotionalState.name }}
                                </option>
                            </select>
                            <div v-if="isFieldDirty('f_emotionalStateId')"
                                class="revertField"><span class="fas fa-history" @click="revertField('f_emotionalStateId')"/></div>
                        </div>
                    </div>

                    <!-- Meeting Details -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label" for="meetingDetailsInput">Meeting Details</label>
                        <div class="col-md-6 entry">
                            <textarea id="meetingDetailsInput"
                                    :class="{ 'form-control': true, 'edited': isFieldDirty('f_meetingDetails') }"
                                    rows="10"
                                    v-model="f_meetingDetails"/>
                            <div v-if="isFieldDirty('f_meetingDetails')"
                                class="revertField"><span class="fas fa-history" @click="revertField('f_meetingDetails')"/></div>
                        </div>
                    </div>

                    <div class="form-group row edit-actions">
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
            // original session report
            sessionReport: null,

            // editable (current unsaved) form state
            f_sessionDate: null,
            f_ratingId: null,
            f_lengthOfSession: null,
            f_activityTypeId: null,
            f_location: null,
            f_safeguardingConcernId: null,
            f_emotionalStateId: null,
            f_meetingDetails: null,

            // disabled elements
            isBusy: false,
        }
    },

    computed: {
         // lookups
         ...mapState('sessionReports/lookups', [
            'safeguardingLookup',
            'sessionRatingsLookup',
            'activityTypesLookup',
            'emotionalStatesLookup'
        ]),

        _originalState() {
            return this.buildFormState(this.sessionReport)            
        }
    },

    watch: {
        sessionReportId: function() {
            this.clearStatus()
            this.intialiseSessionReport()
        },
        sessionReport: function() {
            Object.assign(this.$data, this.buildFormState(this.sessionReport));          
        }
    },

    async created() {
        this.intialiseSessionReport()
        this.initialiseLookups()
    },

    mounted() {
    },

    updated() {
        const vm = this;
        $( ".datepicker.sessiondate" ).datepicker({ 
            dateFormat: 'dd-mm-yy', 
            onSelect: function(dateText) {
                vm.f_sessionDate = dateText
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

        async initialiseLookups() {
            this.try("initialise activity types lookup", async () => await this.initialiseActivityTypesLookup())
            this.try("initialise emotional states lookup", async () => await this.initialiseEmotionalStatesLookup())
            this.try("initialise session ratings lookup", async () => await this.initialiseSessionRatingsLookup())
            this.try("initialise safeguarding lookup", async () => await this.initialiseSafeguardingLookup())
        },

        async intialiseSessionReport() {
            if (!this.sessionReportId) return

            this.sessionReport = null
            this.try("load session report", async( )=> this.sessionReport = await this.fetchSessionReport(this.sessionReportId))
        },

        async handleSaveSessionReport() {
            this.clearStatus()

            const reportData = {
                mentor_id: this.sessionReport.mentor.id,
                mentee_id: this.sessionReport.mentee.id,
                // editable properties
                session_date: this.f_sessionDate,
                rating_id: this.f_ratingId,
                length_of_session: this.f_lengthOfSession,
                activity_type_id: this.f_activityTypeId,
                location: this.f_location,
                safeguarding_concern: this.f_safeguardingConcernId,
                emotional_state_id: this.f_emotionalStateId,
                meeting_details: this.f_meetingDetails
            }

            this.isBusy = true
            try {
                await this.try(
                    "save report",
                    async () => {
                        this.sessionReport = await this.updateSessionReport({ id: this.sessionReport.id, reportData})
                        this.$emit('sessionReportSaved')
                    },
                    {handleSuccess: true}
                )
            } finally {
                this.isBusy = false
            }
        },

        async handleDeleteSessionReport() {
            this.clearStatus()

            this.isBusy = true
            try {
                await this.try(
                    "delete report",
                    async () => {
                        await this.deleteSessionReport(this.sessionReport.id)
                        this.sessionReport = null
                        this.$emit('sessionReportDeleted')
                    },
                    {handleSuccess: true}
                )
            } finally {
                this.isBusy = false
            }
        },

        ...mapActions('sessionReports', [
            'deleteSessionReport' , 
            'updateSessionReport', 
            'fetchSessionReport'
        ]),

        ...mapActions('sessionReports/lookups', [
            'initialiseActivityTypesLookup' , 
            'initialiseEmotionalStatesLookup',
            'initialiseSessionRatingsLookup',
            'initialiseSafeguardingLookup'
        ]),

        buildFormState : sessionReport =>  ( 
            sessionReport ?
                {
                    f_sessionDate: formatDate(parseDate(sessionReport.session_date), SESSION_DATE_FORMAT),
                    f_ratingId: sessionReport.rating.id,
                    f_lengthOfSession: sessionReport.length_of_session.toString(),
                    f_activityTypeId: sessionReport.activity_type.id,
                    f_location: sessionReport.location,
                    f_safeguardingConcernId: sessionReport.safeguarding_concern.id,
                    f_emotionalStateId: sessionReport.emotional_state.id,
                    f_meetingDetails: sessionReport.meeting_details
                }
                : 
                {
                    f_sessionDate: null,
                    f_ratingId: null,
                    f_lengthOfSession: null,
                    f_activityTypeId: null,
                    f_location: null,
                    f_safeguardingConcernId: null,
                    f_emotionalStateId: null,
                    f_meetingDetails: null
                }
        )
    }
};

export default Component;