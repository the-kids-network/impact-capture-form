import _ from 'lodash'
import { List } from 'immutable';
import statusMixin from '../status-box/mixin'
import { dateRange, SEARCH_DATE_FORMAT } from '../../utils/date';
import { mapActions, mapState } from 'vuex';

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
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses">
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
                                    v-for="mentor in mentorsLookupSorted"
                                    :value="mentor.id">
                                    {{ mentor.name }}
                                </option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="menteeSelect">Mentee</label>
                        <select id="menteeSelect" 
                                class="form-control form-control-sm" 
                                v-model="mentee_id">
                                <option :value="null_value" selected>Any</option>
                                <option 
                                    v-for="mentee in menteesLookupSorted"
                                    :value="mentee.id">
                                    {{ mentee.name }}
                                </option>
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
                                    v-for="safeguardingOption in safeguardingLookup"
                                    :value="safeguardingOption.id">
                                    {{ safeguardingOption.label }}
                                </option>
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
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label class="col-form-label" for="sessionDateRangeStartInput">Session Date Start <a @click.prevent="handleClearField('session_date_range_start')" class="fas fa-times"/></label>
                                <input id="sessionDateRangeStartInput"
                                    type="text" 
                                    class="form-control form-control-sm datepicker session-date-range-start"
                                    v-model="session_date_range_start"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="col-form-label" for="sessionDateRangeEndInput">Session Date End <a @click.prevent="handleClearField('session_date_range_end')" class="fas fa-times"/></label>
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
        ...mapState('sessionReports/lookups', ['mentorsLookup', 'safeguardingLookup', 'sessionRatingsLookup']),

        mentorsLookupSorted() {
            return this.mentorsLookup.sortBy(m => m.name)
        },

        menteesLookupSorted() {
            const mentor = this.mentorsLookup.find(m => m.id === this.mentor_id)
            const mentees = mentor ? List(mentor.mentees) : List()
            return mentees.sortBy(m => m.name)
        }
    },

    watch: {
        searchCriteria() {
            this.clearErrors()
            this.applySearchCriteria()
            this.trySearch()
        }
    },

    async created() {
        await this.tryInitialiseLookups()
        this.applySearchCriteria()
        this.trySearch()
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
        handleClearField(field) {
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
            const searchCriteria = this.buildSearchCriteria()
            this.$emit('searchCriteria', searchCriteria)

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

        async trySearch() {
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

            this.isSearching = true
            try {
                await this.try("search session reports", 
                    async () => await this.search(query), 
                )
            } finally {
                this.isSearching = false
            }
        },

        tryInitialiseLookups() {
            this.try("initialise mentors lookup", 
                async () => await this.initialiseMentorsLookup(),
            )
            this.try("initialise safeguarding lookup", 
                async () => await this.initialiseSafeguardingLookup(),
            )
            this.try("initialise session rating lookup", 
                async () => await this.initialiseSessionRatingsLookup(),
            )
        },

        ...mapActions('sessionReports/lookups', 
            ['initialiseMentorsLookup', 'initialiseSafeguardingLookup', 'initialiseSessionRatingsLookup']),

        ...mapActions('sessionReports/search', 
            ['search'])
    }
};

export default Component;