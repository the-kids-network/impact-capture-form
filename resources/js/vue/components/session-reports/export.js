import _ from 'lodash'
import statusMixin from '../status-box/mixin'
import { downloadFileData } from '../../utils/download'
import { mapActions } from 'vuex';

const Component = {

    props: ['label', 'searchParams'],

    mixins: [statusMixin],

    components: {
    },

    template: `
        <div class="session-report-export">  
            <status-box
                ref="status-box"
                class="status"
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses">
            </status-box> 

            <a @click="clearStatus(); handleExportSessionReports()">{{label}}</a>
        </div>
    `,

    data() {
        return {
            csvFileName: "data.csv",
            csvData: null
        }
    },

    computed: {
        
    },

    watch: {
        searchParams() {
            this.csvData = null
        }
    },

    created() {
    },

    mounted() {
    },

    methods: { 
        async handleExportSessionReports() {
            // fetch data if not already cached
            if (!this.csvData) {
                await this.try("export session report data",
                    async () => {
                        const {data, filename} = await this.fetchSessionReportsExport(this.searchParams)
                        this.csvData = data
                        this.csvFileName = filename
                    }
                )
            }

            // initiate download of data
            downloadFileData(this.csvFileName, this.csvData)
        },

        ...mapActions('sessionReports/search', ['fetchSessionReportsExport'])
    },
};

export default Component;