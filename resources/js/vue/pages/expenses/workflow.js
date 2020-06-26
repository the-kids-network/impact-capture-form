import _ from 'lodash'
import ExpenseClaimManage from './manage';
import { mapGetters } from 'vuex';

const Component = {

    props: {
        
    },

    components: {
        'expense-claim-manage': ExpenseClaimManage,
    },

    template: `
        <div class="expense-claim-workflow">
            <div class="container workflow-nav">
                <div class="row page-nav">
                    <div class="navigation-buttons col-8 mt-auto mb-auto text-left ">
                        <span :class="{'first-claim btn btn-primary btn-sm': true, 'disabled': !firstClaim}" 
                                type="button" 
                                aria-label="Beginning"
                                @click='goToClaim(firstClaim)'><span class="fas fa-fast-backward" /></span>
                        <span :class="{'previous-claim btn btn-primary btn-sm': true, 'disabled': !previousClaim}" 
                                type="button" 
                                aria-label="Previous"
                                @click='goToClaim(previousClaim)'><span class="fas fa-backward" /></span>
                        <span :class="{'next-claim btn btn-primary btn-sm': true, 'disabled': !nextClaim}" 
                                type="button" 
                                aria-label="Next"
                                @click='goToClaim(nextClaim)'><span class="fas fa-forward" /></span>
                        <span :class="{'last-claim btn btn-primary btn-sm': true, 'disabled': !lastClaim}" 
                                type="button" 
                                aria-label="End"
                                @click='goToClaim(lastClaim)'><span class="fas fa-fast-forward" /></span>
                    </div>
                    <div class="col mt-auto mb-auto text-right close-workflow">
                        <a type="button" class="btn btn-link" @click.prevent="closeWorkflow">Close</a>
                    </div>
                </div>
            </div>
            
            <div>
                <div v-if="currentClaimId">
                    <expense-claim-manage :expense-claim-id="currentClaimId" />
                </div>
                <div v-else class="card">
                    <div class="card-body">
                        <span>No expense claims selected for workflow. <a type="button" @click.prevent="goToSearch">Try searching again</a>.</span>
                    </div>
                </div>
            </div>
        </div>
    `,

    data() {
        return {
        }
    },

    computed: {
        currentClaimId: {
            get () {
                return this.$store.getters['expenses/currentClaimId']
            },
            set (value) {
                this.$store.commit('expenses/setCurrentClaimId', value)
            }
        },
        
        ...mapGetters('expenses', [
            'previousClaim',
            'nextClaim',
            'firstClaim',
            'lastClaim'
        ])
    },

    watch: {},

    created() {},

    mounted() {},

    methods: { 
        goToClaim(claimId) {
            if (claimId) this.currentClaimId = claimId
        },
        closeWorkflow() {    
            this.$router.go(-1)
        },
        goToSearch() {    
            this.$router.push({ name: 'expenses-search'})
        }
    },
};

export default Component;