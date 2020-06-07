import _ from 'lodash'
import Vue from 'vue'
import Vuex from 'vuex'
import moment from 'moment'

Vue.use(Vuex)

const store = new Vuex.Store (
    {
        state: {
            search: {
                mentorId: null,
                menteeId: null,
                sessionDateRangeStart: moment().subtract(1, 'months').format('DD-MM-YYYY'),
                sessionDateRangeEnd:  moment().format('DD-MM-YYYY'),
            },
            sessionReports: {
                list: [],
                currentlySelected: null,
            }
        },
        mutations: {
            set(state, payload) {
                _.set(state, payload.path, payload.value);
            }
        },
        getters: {
           
        }
    }
)

export default store