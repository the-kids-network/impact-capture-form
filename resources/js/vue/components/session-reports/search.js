import _ from 'lodash'
import { List } from 'immutable';
import { extractErrors } from '../utils/api'
import statusMixin from '../status-box/mixin'

const Component = {

    props: {
        mentors: {
            default: () => []        
        },
        searchCriteria: null
    },

    mixins: [statusMixin],

    components: {
    },

    template: `
        <div class="session-report-search">      
            <status-box
                ref="status-box"
                class="status"
                :errors="errors">
            </status-box>   

            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="mentorSelect">Mentor</label>
                        <select id="mentorSelect" 
                                class="form-control form-control-sm" 
                                v-model="mentorId">
                                <option value="" selected>Any</option>
                                <option 
                                    v-for="mentor in mentors"
                                    :value="mentor.id">
                                    {{ mentor.name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="menteeSelect">Mentee</label>
                        <select id="menteeSelect" 
                                class="form-control form-control-sm" 
                                v-model="menteeId">
                                <option value="" selected>Any</option>
                                <option 
                                    v-for="mentee in _selectableMentees"
                                    :value="mentee.id">
                                    {{ mentee.name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> 
                <div class="form-row">
                    <span class="col-md-12">Session date range (dates inclusive)</span>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="sessionDateRangeStartInput">Start</label>
                        <input id="sessionDateRangeStartInput"
                            type="text" 
                            class="form-control form-control-sm datepicker session-date-range-start"
                            v-model="sessionDateRangeStart"
                            autocomplete="off">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label" for="sessionDateRangeEndInput">End</label>
                        <input id="sessionDateRangeEndInput"
                            type="text" 
                            class="form-control form-control-sm datepicker session-date-range-end"
                            v-model="sessionDateRangeEnd"
                            autocomplete="off">
                    </div>
                </div> 
                <div class="form-group row">
                    <div class="col-md-4">
                        <span v-on:click="search()" class="search btn btn-primary " :disabled="isSearching">
                        <span class="fas fa-search" /> Search</span>
                    </div>
                </div>
            </form>
        </div>
    `,

    data() {
        return {
            isSearching: false,

            // search criteria
            mentorId: null,
            menteeId: null,
            sessionDateRangeStart: null,
            sessionDateRangeEnd: null,
        }
    },

    computed: {
        _selectableMentees() {
            const mentor = List(this.mentors).find(m => m.id === this.mentorId)
            return mentor ? mentor.mentees : []
        }
    },

    watch: {
        // reset mentee if mentor (re)selected
        mentorId: function (val) {
            this.menteeId = null;
        }
    },

    created() {
        this.doInitialSearch()
    },

    mounted() {
        var vm = this
        $(document).ready(function() {
            $(function() {
                // replace with a decent vuejs component
                $(".datepicker.session-date-range-start").datepicker({ 
                    dateFormat: 'dd-mm-yy', 
                    onSelect: dateText => vm.sessionDateRangeStart = dateText
                });
                $(".datepicker.session-date-range-end").datepicker({ 
                    dateFormat: 'dd-mm-yy', 
                    onSelect: dateText => vm.sessionDateRangeEnd = dateText
                });
            });
        });
    },

    methods: { 
        publishSessionReports(sessionReports) {
            this.$emit('results', {
                sessionReports: sessionReports,
                searchCriteria: {
                    mentorId: this.mentorId,
                    menteeId: this.menteeId,
                    sessionDateRangeStart: this.sessionDateRangeStart,
                    sessionDateRangeEnd: this.sessionDateRangeEnd
                }
            })
        },

        doInitialSearch() {
            if (this.searchCriteria) {
                // if criteria supplied to component, use it
                this.mentorId =  this.searchCriteria.mentorId
                this.menteeId = this.searchCriteria.menteeId
                this.sessionDateRangeStart = this.searchCriteria.sessionDateRangeStart
                this.sessionDateRangeEnd = this.searchCriteria.sessionDateRangeEnd
            } else {
                // otherwise default to last 1 month of data
                this.sessionDateRangeStart = moment().subtract(1, 'months').format('DD-MM-YYYY')
                this.sessionDateRangeEnd = moment().format('DD-MM-YYYY')
            }
    
            this.search()
        },

        async search() {
            this.clearErrors()

            const query = {
                ...(this.mentorId ? {'mentor_id': this.mentorId}: {}),
                ...(this.menteeId ? {'mentee_id': this.menteeId}: {}),
                ...(this.sessionDateRangeStart ? {'session_date_range_start': this.sessionDateRangeStart}: {} ),
                ...(this.sessionDateRangeEnd ? {'session_date_range_end': this.sessionDateRangeEnd}: {} ),
                // large field that doesnt need to be returned at this point
                'exclude_fields': ['meeting_details']
            }

            try {
                this.isSearching = true
                const results = await this.getSessionReports(query)
                this.publishSessionReports(results)
            } catch (e) {
                this.addErrors(extractErrors({e, defaultMsg: `Unknown problem searching session reports`}))
            } finally {
                this.isSearching = false
            }
        },

        async getSessionReports(queryParameters) {
            return (await axios.get(`/session-reports`, { params: queryParameters })).data
        }
    }
};

export default Component;