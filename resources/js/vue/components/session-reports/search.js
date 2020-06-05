import _ from 'lodash'
import Popper from 'vue-popperjs';
import 'vue-popperjs/dist/vue-popper.css';

// search - auto hiding div
    // mentors, mentees, dates, rating, safeguarding concern
    // url based search / saveable search
    // cache results

// display results
    // export to csv
// display workflow
    // display workflow item


// rest services
    // get ratings
    // get safeguarding
    // search session reports
    // get one session report
const Component = {

    props: ['ratingsLookup'],

    components: {
        'popper': Popper
    },


    template: `
        <div class="session-report-search">            
            <status-box
                ref="status-box"
                class="documents-status"
                :successes="successes"
                :errors="errors">
            </status-box>   

            <h1>HELLO WORLD!!</h1>

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

    async created() {
    },

    mounted() {
        var vm = this
        $(document).ready(function() {
            $(function() {
                $( ".datepicker.sessiondate" ).datepicker({ 
                    dateFormat: 'dd-mm-yy', 
                    onSelect: function(dateText) {
                        vm.sessionDate = dateText
                    }
                });
            });
        });
    },

    methods: { 
      
    }
};

export default Component;