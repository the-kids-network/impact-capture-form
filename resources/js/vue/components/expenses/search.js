import _ from 'lodash'
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
        <div class="expense-claim-search">      
            <status-box
                ref="status-box"
                class="status"
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses">
            </status-box>   

            <form class="form">
                <div class="form-row">
                    <div class="form-group col-md-3" v-if="isInternalUser">
                        <label class="col-form-label" for="mentorSelect">Mentor</label>
                        <select id="mentorSelect" 
                                class="form-control form-control-sm" 
                                v-model="mentor_id">
                                <option :value="null_value" selected>Any</option>
                                <option 
                                    v-for="mentor in mentorsLookupSorted"
                                    :value="mentor.id">
                                    {{ mentor.name }}
                                </option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="col-form-label" for="statusSelect">Status</label>
                        <select id="statusSelect" 
                                class="form-control form-control-sm" 
                                v-model="status" multiple>
                                <option 
                                    v-for="s in statusLookup"
                                    :value="s">
                                    {{ s }}
                                </option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <label class="col-form-label" for="createdDateRangeStartInput">Created Date Start <a @click.prevent="handleClearField('created_date_range_start')" class="fas fa-times"/></label>
                                <input id="createdDateRangeStartInput"
                                    type="text" 
                                    class="form-control form-control-sm datepicker created-date-range-start"
                                    v-model="created_date_range_start"
                                    autocomplete="off" />
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="col-form-label" for="createdDateRangeEndInput">Created Date End <a @click.prevent="handleClearField('created_date_range_end')" class="fas fa-times"/></label>
                                <input id="createdDateRangeEndInput"
                                    type="text" 
                                    class="form-control form-control-sm datepicker created-date-range-end"
                                    v-model="created_date_range_end"
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
            status: [],
            created_date_range_start: null,
            created_date_range_end: null
        }
    },

    computed: {
        ...mapState('sessionReports/lookups', ['mentorsLookup']),
        ...mapState('expenses/lookups', ['statusLookup']),

        mentorsLookupSorted() {
            return this.mentorsLookup.sortBy(m => m.name)
        },
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
                $(".datepicker.created-date-range-start").datepicker({ 
                    dateFormat: 'dd-mm-yy', 
                    onSelect: dateText => vm.created_date_range_start = dateText
                });
                $(".datepicker.created-date-range-end").datepicker({ 
                    dateFormat: 'dd-mm-yy', 
                    onSelect: dateText => vm.created_date_range_end = dateText
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
            this.created_date_range_start = startDate.format(dateFormat)
            this.created_date_range_end = endDate.format(dateFormat)
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
                status: this.status,
                created_date_range_start: this.created_date_range_start,
                created_date_range_end: this.created_date_range_end
            }
        },

        async trySearch() {
            const query = {
                mentor_id: this.mentor_id,
                status: !(_.isEmpty(this.status)) ? this.status : null,
                created_date_range_start: !(_.isEmpty(this.created_date_range_start)) ? this.created_date_range_start : null,
                created_date_range_end: !(_.isEmpty(this.created_date_range_end)) ? this.created_date_range_end : null,
            }

            this.isSearching = true
            try {
                await this.try("search expense claims", 
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
            this.try("initialise status lookup", 
                async () => await this.initialiseStatusLookup(),
            )
        },

        ...mapActions('sessionReports/lookups', 
            ['initialiseMentorsLookup']),

        ...mapActions('expenses/lookups', 
            ['initialiseStatusLookup']),

        ...mapActions('expenses/search', 
            ['search'])
    }
};

export default Component;