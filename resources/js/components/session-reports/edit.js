import _ from 'lodash'
import Popper from 'vue-popperjs';
import 'vue-popperjs/dist/vue-popper.css';
import df from 'dateformat'

const Component = {

    props: ['report', 'activityTypesLookup', 'emotionalStatesLookup', 'ratingsLookup'],

    components: {
        'popper': Popper
    },

    template: `
        <div class="session-report-editor">

            <a :href="'/report/' + this.report.id">Go back to session report</a>
            
            <status-box
                ref="status-box"
                class="documents-status"
                :successes="successes"
                :errors="errors">
            </status-box>   

            <form class="form-horizontal" role="form" method="POST" :action="'/report/' + report.id">
                <input type="hidden" name="_method" value="PUT"/>

                <!-- Mentee's Name -->
                <div class="form-group row">
                    <label class="col-md-4 col-form-label" for="menteeInput">Mentee</label>
                    <div class="col-md-6">
                        <select id="menteeInput" class="form-control" name="mentee_id" disabled>
                            <option
                                :value="report.mentee.id" selected>
                                {{ report.mentee.first_name }} {{ report.mentee.last_name }}
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
                               :class="'form-control datepicker sessiondate ' + dirtyClass('sessionDate')"
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
                                :class="'form-control ' + dirtyClass('ratingId')"
                                v-model="ratingId">
                            <option v-for="rating in ratingsLookup"
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
                               :class="'form-control ' + dirtyClass('lengthOfSession')"
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
                                :class="'form-control ' + dirtyClass('activityTypeId')"
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
                               :class="'form-control ' + dirtyClass('location')" 
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
                                :class="'form-control ' + dirtyClass('safeguardingConcern')"
                                v-model="safeguardingConcern">
                            <option v-for="item in safeguardingLookup"
                                    :value="item.id">
                                    {{ item.value }}
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
                                :class="'form-control ' + dirtyClass('emotionalStateId')"
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
                                  :class="'form-control ' + dirtyClass('meetingDetails')" rows="10"
                                  v-model="meetingDetails"/>
                        <div v-if="isDirty('meetingDetails')"
                            class="revert"><span class="fas fa-history" @click="revert('meetingDetails')"/></div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4">
                        <span v-on:click="save()" class="save btn btn-success " :disabled="isSaving">
                        <span class="fas fa-save" /> Save</span>
                    </div>
                </div>
            </form>
        </div>
    `,

    data() {
        return {
            successes: [],
            errors: [],
            safeguardingLookup: [
                {
                    id: 0,
                    value: "No"
                },
                {
                    id: 1,
                    value: "Yes - Serious concern (please complete safeguarding cause for concern form)"
                },
                {
                    id: 2,
                    value: "Yes - Mild concern (please outline in report)"
                }
            ],

            originalState: this.buildOriginalState(this.report),

            // editable (current) state
            sessionDate: this.formatDate(this.report.session_date),
            ratingId: this.report.rating_id,
            lengthOfSession: this.report.length_of_session.toString(),
            activityTypeId: this.report.activity_type_id,
            location: this.report.location,
            safeguardingConcern: this.report.safeguarding_concern,
            emotionalStateId: this.report.emotional_state_id,
            meetingDetails: this.report.meeting_details,

            // saving
            isSaving: false,
        }
    },

    computed: {
        
    },

    watch: {
        
    },

    async created() {
    },

    mounted() {
        var vm = this
        $(document).ready(function() {
            $(function() {
                $( ".datepicker.sessiondate" ).datepicker({ 
                    dateFormat: 'dd-mm-yy', 
                    onSelect: function(dateText) {
                        vm.sessionDate = dateText
                    }
                });
            });
        });
    },

    methods: { 
        scrollTo(refName) {
            var element = this.$refs[refName];
            var top = element.offsetTop;
            window.scrollTo(0, top);
        },

        clearStatus() {
            this.errors = [];
            this.successes = [];
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return df(date, "dd-mm-yyyy")
        },

        isDirty(editableFieldName) {
            return this.originalState[editableFieldName] !== this[editableFieldName]
        },

        dirtyClass(editableFieldName) {
            return this.isDirty(editableFieldName) ? 'edited' : ''
        },

        revert(editableFieldName) {
            if (this.hasOwnProperty(editableFieldName)) {
                this[editableFieldName] = this.originalState[editableFieldName]
            }
        },

        buildOriginalState(report) {
            return {
                reportId: report.id,
                mentorId: report.mentor_id,
                menteeId: report.mentee_id,
                sessionDate: this.formatDate(report.session_date),
                ratingId: report.rating_id,
                lengthOfSession: report.length_of_session.toString(),
                activityTypeId: report.activity_type_id,
                location: report.location,
                safeguardingConcern: report.safeguarding_concern,
                emotionalStateId: report.emotional_state_id,
                meetingDetails: report.meeting_details
            }
        },

        async save() {
            this.clearStatus()

            const reportBody = {
                mentor_id: this.originalState.mentorId,
                mentee_id: this.originalState.menteeId,
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
                const updatedReport = await this.updateSessionReport(this.originalState.reportId, reportBody)
                this.originalState = this.buildOriginalState(updatedReport)
                this.successes = ['Report was saved successfully']
            } catch (e) {
                if (_.has(e, 'response.data.errors')) {
                    const errors = e.response.data.errors
                    this.errors = Object.values(errors).reduce((a, b) => a.concat(b), []);
                } else {
                    this.errors = ['Unknown problem saving the session report']
                }
            } finally {
                this.isSaving = false
                this.scrollTo('status-box')
            }
        },

        async updateSessionReport(id, sessionReport) {
            return (await axios.put(`/report/${id}`, sessionReport)).data
        }
    }
};

export default Component;