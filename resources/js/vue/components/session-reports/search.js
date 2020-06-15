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
                <div class="form-row" v-if="isInternalUser">
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="mentorSelect">Mentor</label>
                        <select id="mentorSelect" 
                                class="form-control form-control-sm" 
                                v-model="mentor_id"
                                @change="mentee_id = null">
                                <option :value="null_value" selected>Any</option>
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
                                v-model="mentee_id">
                                <option :value="null_value" selected>Any</option>
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
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="safeguardingSelect">Safeguarding</label>
                        <select id="safeguardingSelect" 
                                class="form-control form-control-sm" 
                                v-model="safeguarding_id">
                                <option :value="null_value" selected>Any</option>
                                <option 
                                    v-for="safeguardingOption in safeguardingOptions"
                                    :value="safeguardingOption.id">
                                    {{ safeguardingOption.label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="safeguardingSelect">Rating</label>
                        <select id="ratingSelect" 
                                class="form-control form-control-sm" 
                                v-model="session_rating_id">
                                <option :value="null_value" selected>Any</option>
                                <option 
                                    v-for="item in sessionRatingsLookup"
                                    v-if="item.selectable"
                                    :value="item.id">
                                    {{ item.value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label class="col-form-label" for="sessionDateRangeStartInput">Session Date Start <a @click.prevent="handleClearData('session_date_range_start')" class="fas fa-times"/></label>
                                <input id="sessionDateRangeStartInput"
                                    type="text" 
                                    class="form-control form-control-sm datepicker session-date-range-start"
                                    v-model="session_date_range_start"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="col-form-label" for="sessionDateRangeEndInput">Session Date End <a @click.prevent="handleClearData('session_date_range_end')" class="fas fa-times"/></label>
                                <input id="sessionDateRangeEndInput"
                                    type="text" 
                                    class="form-control form-control-sm datepicker session-date-range-end"
                                    v-model="session_date_range_end"
                                    autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-1 mb-auto mt-auto">
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
            null_value: null,
            isSearching: false,

            // lookups
            mentors:[],
            safeguardingOptions: [],
            sessionRatingsLookup: [],

            // default search state
            mentor_id: null,
            mentee_id: null,
            safeguarding_id: null,
            session_rating_id: null,
            session_date_range_start: null,
            session_date_range_end: null
        }
    },

    computed: {
        selectableMentees() {
            const mentor = List(this.mentors).find(m => m.id === this.mentor_id)
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
        await this.initialiseSafeguardingOptions()
        await this.initialiseSessionRatingsLookup()
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
                    onSelect: dateText => vm.session_date_range_start = dateText
                });
                $(".datepicker.session-date-range-end").datepicker({ 
                    dateFormat: 'dd-mm-yy', 
                    onSelect: dateText => vm.session_date_range_end = dateText
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
            this.session_date_range_start = startDate.format(dateFormat)
            this.session_date_range_end = endDate.format(dateFormat)
        },

        handleClickSearch() {
            this.publishSearchCriteria(this.buildSearchCriteria())
        },

        applySearchCriteria() {
            Object.assign(this.$data, this.searchCriteria)
        },

        buildSearchCriteria() {
            return { 
                mentor_id: this.mentor_id,
                mentee_id: this.mentee_id,
                safeguarding_id: this.safeguarding_id,
                session_rating_id: this.session_rating_id,
                session_date_range_start: this.session_date_range_start,
                session_date_range_end: this.session_date_range_end
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
                mentor_id: this.mentor_id,
                mentee_id: this.mentee_id,
                safeguarding_id: this.safeguarding_id,
                session_rating_id: this.session_rating_id,
                session_date_range_start: !(_.isEmpty(this.session_date_range_start)) ? this.session_date_range_start : null,
                session_date_range_end: !(_.isEmpty(this.session_date_range_end)) ? this.session_date_range_end : null,
                // large field that doesnt need to be returned at this point
                exclude_fields: ['meeting_details']
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

        async initialiseSafeguardingOptions() {
            try {

                this.safeguardingOptions = await this.fetchSafeguardingOptions()
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem getting safeguarding lookup`})
                this.addErrors({errs: messages})
            }
        },

        async initialiseSessionRatingsLookup() {
            try {

                this.sessionRatingsLookup = await this.fetchSessionRatingsLookup()
            } catch (e) {
                const messages = extractErrors({e, defaultMsg: `Problem getting session ratings lookup`})
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
        },

        async fetchSafeguardingOptions() {
            return (await axios.get(`/api/safeguarding-options`)).data
        },

        async fetchSessionRatingsLookup() {
            return (await axios.get(`/api/session-ratings`)).data
        }

    }
};

export default Component;