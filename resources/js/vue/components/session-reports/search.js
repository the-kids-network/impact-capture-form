import _ from 'lodash'
import { List } from 'immutable';
import { extractErrors } from '../../utils/api'
import statusMixin from '../status-box/mixin'
import { SEARCH_DATE_FORMAT } from './consts'
import { dateRange } from '../../utils/date';

const Component = {

    props: {
        searchCriteria: {
            default: () => {}
        }
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

            <form class="form">
                <div class="form-row border-bottom" v-if="isInternalUser">
                    <div class="form-group col-md-4">
                        <label class="col-form-label" for="mentorSelect">Mentor</label>
                        <select id="mentorSelect" 
                                class="form-control form-control-sm" 
                                v-model="mentorId"
                                @change="menteeId = null">
                                <option value="" selected>Any</option>
                                <option 
                                    v-for="mentor in mentors"
                                    :value="mentor.id">
                                    {{ mentor.name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="menteeSelect">Mentee</label>
                        <select id="menteeSelect" 
                                class="form-control form-control-sm" 
                                v-model="menteeId">
                                <option value="" selected>Any</option>
                                <option 
                                    v-for="mentee in selectableMentees"
                                    :value="mentee.id">
                                    {{ mentee.name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> 
                <div class="form-row">
                    <span class="col-md-12">Session date range (dates inclusive)</span>
                    <div class="col-md-7">
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label class="col-form-label" for="sessionDateRangeStartInput">Start <a @click.prevent="handleClearData('sessionDateRangeStart')" class="fas fa-times"/></label>
                                <input id="sessionDateRangeStartInput"
                                    type="text" 
                                    class="form-control form-control-sm datepicker session-date-range-start"
                                    v-model="sessionDateRangeStart"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="col-form-label" for="sessionDateRangeEndInput">End <a @click.prevent="handleClearData('sessionDateRangeEnd')" class="fas fa-times"/></label>
                                <input id="sessionDateRangeEndInput"
                                    type="text" 
                                    class="form-control form-control-sm datepicker session-date-range-end"
                                    v-model="sessionDateRangeEnd"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 offset-md-1 mb-auto mt-auto">
                        <div class="row date-quick-fill">
                            <div class="col-md-12">
                                <span class="btn btn-secondary btn-sm" @click="handleClickDateQuickFill('today')">Today</span>
                                <span class="btn btn-secondary btn-sm" @click="handleClickDateQuickFill('yesterday')">Yesterday</span>
                            </div>
                            <div class="col-md-12">
                                <span class="btn btn-secondary btn-sm" @click="handleClickDateQuickFill('past-1-week')">Past 1 week</span>
                                <span class="btn btn-secondary btn-sm" @click="handleClickDateQuickFill('past-1-month')">Past 1 month</span>
                                <span class="btn btn-secondary btn-sm" @click="handleClickDateQuickFill('past-3-month')">Past 3 months</span>
                            </div>
                        </div>
                    </div> 
                </div> 
                <div class="form-row mt-2">
                    <div class="col-md-4">
                        <span v-on:click="handleClickSearch" class="search btn btn-primary " :disabled="isSearching">
                        <span class="fas fa-search" /> Search</span>
                    </div>
                </div>
            </form>
        </div>
    `,

    data() {
        return {
            isSearching: false,

            // lookups
            mentors:[],

            // default search state
            mentorId: null,
            menteeId: null,
            sessionDateRangeStart: null,
            sessionDateRangeEnd: null
        }
    },

    computed: {
        selectableMentees() {
            const mentor = List(this.mentors).find(m => m.id === this.mentorId)
            return mentor ? mentor.mentees : []
        }
    },

    watch: {
        searchCriteria() {
            this.clearErrors()
            this.applySearchCriteria()
            this.search()
        }
    },

    async created() {
        await this.initialiseMentors()
        this.applySearchCriteria()
        this.search()
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
        handleClearData(field) {
            if (this.hasOwnProperty(field)) {
                this[field] = null
            }
        },

        handleClickDateQuickFill(type) {
            const dateFormat = SEARCH_DATE_FORMAT
            const {startDate, endDate} = dateRange(type)
            this.sessionDateRangeStart = startDate.format(dateFormat)
            this.sessionDateRangeEnd = endDate.format(dateFormat)
        },

        handleClickSearch() {
            this.publishSearchCriteria(this.buildSearchCriteria())
        },

        applySearchCriteria() {
            this.mentorId = this.searchCriteria.mentorId ? this.searchCriteria.mentorId : null
            this.menteeId = this.searchCriteria.menteeId ? this.searchCriteria.menteeId : null
            this.sessionDateRangeStart = this.searchCriteria.sessionDateRangeStart ? this.searchCriteria.sessionDateRangeStart : null
            this.sessionDateRangeEnd = this.searchCriteria.sessionDateRangeEnd ? this.searchCriteria.sessionDateRangeEnd : null
        },

        buildSearchCriteria() {
            return { 
                ...(this.mentorId ? {mentorId: this.mentorId}: {}),
                ...(this.menteeId ? {menteeId: this.menteeId}: {}),
                ...(this.sessionDateRangeStart ? {sessionDateRangeStart: this.sessionDateRangeStart}: {}),
                ...(this.sessionDateRangeEnd ? {sessionDateRangeEnd: this.sessionDateRangeEnd}: {}),
            }
        },

        publishSearchCriteria(searchCriteria) {
            this.$emit('searchCriteria', searchCriteria)
        },

        publishSearchResults(results) {
            this.$emit('searchResults', List(results))
        },

        clearSearchResults() {
            this.$emit('searchResults', [])
        },

        async search() {
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
                const results = await this.fetchSessionReports(query)
                this.publishSearchResults(results)
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem searching session reports`})
                this.addErrors({errs: messages})
            } finally {
                this.isSearching = false
            }
        },

        async initialiseMentors() {
            try {
                this.mentors = await this.fetchMentors()
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem getting mentors lookup`})
                this.addErrors({errs: messages})
            }
        },

        /**
         * API data
         */
        async fetchSessionReports(queryParameters) {
            return (await axios.get(`/api/session-reports`, { params: queryParameters })).data
        },

        async fetchMentors() {
            return (await axios.get(`/api/users`, { params: {role: 'mentor'} })).data
        }
    }
};

export default Component;