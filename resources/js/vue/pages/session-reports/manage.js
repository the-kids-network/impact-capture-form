import SessionReportManage from '../../components/session-reports/manage'

import _ from 'lodash'

const Component = {

    props: ['sessionReportId'],

    components: {
        'session-report-manage': SessionReportManage,
    },

    template: `
        <div>
            <div class="row">
                <div class="col-md-12">
                    <nav class="nav page-nav">
                        <a class="nav-link" type="button" @click="goBack">Go back</a>
                    </nav>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Session Report: {{ sessionReportId }}
                </div>
                <div class="card-body">
                    <session-report-manage
                        :session-report-id=sessionReportId>
                    </session-report-manage>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
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
        goBack() {
            this.$router.go(-1)
        }
    },
};

export default Component;