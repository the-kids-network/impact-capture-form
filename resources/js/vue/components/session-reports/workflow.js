import SessionReportView from './view'
import SessionReportEdit from './edit'
import { List } from 'immutable'

const Component = {

    props: {
    },

    components: {
        'session-report-view': SessionReportView,
        'session-report-edit': SessionReportEdit
    },

    template: `
        <div class="session-report-workflow">     
            <div class="row">
                <div class="col-md-12">
                    <nav class="nav page-nav">
                        <a class="nav-link" type="button" @click="closeWorkflow">Close workflow</a>
                    </nav>
                </div>
            </div>
            <div class="controls"> 
                <div class="row navigation">
                    <div class="col-md-6">
                        <span :class="{'btn': true, 'btn-primary': true, 'disabled': !previousSessionReportId}" 
                            type="button" 
                            @click='goToSessionReport(previousSessionReportId)'>Previous</span>
                        <span :class="{'btn': true, 'btn-primary': true, 'disabled': !nextSessionReportId}" 
                            type="button" 
                            @click='goToSessionReport(nextSessionReportId)'>Next</span>
                    </div>
                    <div class="col-md-6">
                        <span :class="{'btn': true, 'btn-link': true, 'disabled': false}" 
                            type="button" 
                            @click="switchMode('edit')">Edit</span>
                        <span :class="{'btn': true, 'btn-link': true, 'disabled': false}" 
                            type="button" 
                            @click="switchMode('view')">View</span>
                    </div>
                </div>      
            </div>
            <session-report-view
                v-if="mode === 'view'"
                :session-report-id=activeSessionReportId>
            </session-report-view>
            <session-report-edit
                v-if="mode === 'edit'"
                :session-report-id=activeSessionReportId>
            </session-report-edit>
        </div>
    `,

    data() {
        return {
            // possible mode: view, edit
            mode: "view",
        }
    },

    computed: {
        sessionReportList()  {
            return List(this.$store.state.sessionReports.list)
        },
        activeSessionReportId: {
            get () {
                return this.$store.state.sessionReports.currentlySelected
            },
            set (value) {
                this.$store.commit('set', {path: 'sessionReports.currentlySelected', value: value})
            }
        },
        previousSessionReportId() {
            const key = getKeyOfItemWithId(this.sessionReportList, this.activeSessionReportId)
            const previousKey = key - 1
            return (previousKey >= 0) ? this.sessionReportList.get(previousKey).id : null
        },

        nextSessionReportId() {
            const key = getKeyOfItemWithId(this.sessionReportList, this.activeSessionReportId)
            const nextKey = key + 1
            return (nextKey < this.sessionReportList.size) ? this.sessionReportList.get(nextKey).id : null
        }
    },

    watch: {
        
    },

    created() {
    },

    mounted() {
        
    },

    methods: { 
        switchMode(mode) {
            this.mode = mode
        },
        
        goToSessionReport(sessionReportId) {
            if (sessionReportId) this.activeSessionReportId = sessionReportId
        },

        closeWorkflow() {
            this.$emit('close')
        }
    }
};

const getKeyOfItemWithId = (list, id) => list.findKey(item => item.id === id)


export default Component;