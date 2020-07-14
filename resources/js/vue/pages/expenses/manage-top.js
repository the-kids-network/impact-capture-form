import ExpenseClaimManage from './manage'

import _ from 'lodash'

const Component = {

    props: ['expenseClaimId'],

    components: {
        'expense-claim-manage': ExpenseClaimManage,
    },

    template: `
        <div>
            <div class="row">
                <div class="col-md-12">
                    <nav class="nav page-nav">
                        <a class="nav-link" type="button" @click.prevent="goBack">Go back</a>
                    </nav>
                </div>
            </div>
            <div>
                <expense-claim-manage
                    :expense-claim-id=expenseClaimId />
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