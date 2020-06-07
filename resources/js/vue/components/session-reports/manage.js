import SessionReportView from './view'
import SessionReportEdit from './edit'

import _ from 'lodash'

const Component = {

    props: ['sessionReportId'],

    components: {
        'session-report-view': SessionReportView,
        'session-report-edit': SessionReportEdit
    },

    template: `
        <div class="session-report-manage">  
            <div class="row edit-view-toggle">
                <div class="col-md mt-auto mb-auto text-left mode-selector">
                    <span :class="{'btn btn-primary btn-sm': true, 'disabled': false}" 
                            type="button" 
                            @click="switchMode('edit')">Edit</span>
                    <span :class="{'btn btn-primary btn-sm': true, 'disabled': false}" 
                            type="button" 
                            @click="switchMode('view')">View</span>
                </div>
            </div>

            <session-report-view
                v-if="mode === 'view'"
                :session-report-id=sessionReportId>
            </session-report-view>
            <session-report-edit
                v-if="mode === 'edit'"
                :session-report-id=sessionReportId>
            </session-report-edit>
        </div>
    `,

    data() {
        return {
            mode: "view",
        }
    },

    computed: {
        
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
        }
    },
};

export default Component;