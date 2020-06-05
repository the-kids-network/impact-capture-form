import SessionReportView from './view'
import SessionReportEdit from './edit'
import { List } from 'immutable'

const Component = {

    props: {
        sessionReports: {
            default: () => []        
        },

        initialSessionReportId: {
            default: () => undefined
        }
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
                        <span :class="{'btn': true, 'btn-primary': true, 'disabled': !_previousSessionReportId}" 
                            type="button" 
                            @click='goToSessionReport(_previousSessionReportId)'>Previous</span>
                        <span :class="{'btn': true, 'btn-primary': true, 'disabled': !_nextSessionReportId}" 
                            type="button" 
                            @click='goToSessionReport(_nextSessionReportId)'>Next</span>
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
                :session-report-id=currentSessionReportId>
            </session-report-view>
            <session-report-edit
                v-if="mode === 'edit'"
                :session-report-id=currentSessionReportId>
            </session-report-edit>
        </div>
    `,

    data() {
        return {
            // possible mode: view, edit
            mode: "view",
            sessionReportList: List(this.sessionReports),
            currentSessionReportId: this.initialSessionReportId
        }
    },

    computed: {
        _previousSessionReportId() {
            const key = getKeyOfItemWithId(this.sessionReportList, this.currentSessionReportId)
            const previousKey = key - 1
            return (previousKey >= 0) ? this.sessionReportList.get(previousKey).id : null
        },

        _nextSessionReportId() {
            const key = getKeyOfItemWithId(this.sessionReportList, this.currentSessionReportId)
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
            if (sessionReportId) this.currentSessionReportId = sessionReportId
        },

        closeWorkflow() {
            this.$emit('close')
        }
    }
};

const getKeyOfItemWithId = (list, id) => list.findKey(item => item.id === id)


export default Component;