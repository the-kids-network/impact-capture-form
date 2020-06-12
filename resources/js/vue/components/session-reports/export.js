import _ from 'lodash'
import statusMixin from '../status-box/mixin'
import { extractErrors } from '../utils/api'
import { downloadFileData } from '../utils/download'
import contentDisposition from 'content-disposition'

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
                :errors="errors">
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
            // search params
            const params = {
                ...(this.searchParams.mentorId ? {'mentor_id': this.searchParams.mentorId}: {}),
                ...(this.searchParams.menteeId ? {'mentee_id': this.searchParams.menteeId}: {}),
                ...(this.searchParams.sessionDateRangeStart ? {'session_date_range_start': this.searchParams.sessionDateRangeStart}: {} ),
                ...(this.searchParams.sessionDateRangeEnd ? {'session_date_range_end': this.searchParams.sessionDateRangeEnd}: {} ),
            }

            // fetch data if not already cached
            if (!this.csvData) {
                try {
                    const {data, filename} = await this.fetchSessionReportsExport(params)
                    this.csvData = data
                    this.csvFileName = filename
                } catch (e) {
                    const messages = extractErrors({e, defaultMsg: `Problem exporting session report data`})
                    this.addErrors({errs: messages, scrollToPos: 'bottom'})
                    return
                }
            }

            // initiate download of data
            downloadFileData(this.csvFileName, this.csvData)
        },

        async fetchSessionReportsExport(params) {
            const response = await axios({
                method: 'GET',
                url: '/api/session-reports/export',
                params: params,
                responseType: 'blob',
            })

            const data = _.get(response, 'data')

            const filename = _.has(response, 'headers.content-disposition') 
                ? contentDisposition.parse(_.get(response, 'headers.content-disposition')).parameters.filename
                : null

            return {data, filename}
        }
    },
};

export default Component;