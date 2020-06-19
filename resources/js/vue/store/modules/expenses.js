import _ from 'lodash'

const module = {
    namespaced: true,

    actions: {

        async fetchExpenseClaimsForSessionReport({}, sessionReportId) {
            return (await axios.get(`/api/expense-claims`, { params: {session_id: sessionReportId} } )).data
        }
    }
}

export default module