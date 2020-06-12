import SessionReportViewToggler from './view-toggler'

import _ from 'lodash'

const Component = {

    props: ['sessionReportId'],

    components: {
        'session-view-toggler': SessionReportViewToggler,
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
        
            <div>
                <session-view-toggler
                    :session-report-id=sessionReportId>
                </session-view-toggler>
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