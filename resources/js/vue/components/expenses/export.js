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
        <div class="expense-claim-export">  
            <status-box
                ref="status-box"
                class="status"
                :errors="errors"
                @clearErrors="clearErrors"
                @clearSuccesses="clearSuccesses">
            </status-box> 

            <a @click="clearStatus(); handleExport()">{{label}}</a>
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
        async handleExport() {
            // fetch data if not already cached
            if (!this.csvData) {
                await this.try("export expense claims data",
                    async () => {
                        const {data, filename} = await this.fetchExpenseClaimsExport(this.searchParams)
                        this.csvData = data
                        this.csvFileName = filename
                    }
                )
            }

            // initiate download of data
            downloadFileData(this.csvFileName, this.csvData)
        },

        ...mapActions('expenses/search', ['fetchExpenseClaimsExport'])
    },
};

export default Component;