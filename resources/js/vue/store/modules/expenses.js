import _ from 'lodash'
import { List } from 'immutable'
import contentDisposition from 'content-disposition'

const module = {
    namespaced: true,

    state: {
        claims: List(),
        currentClaimId: null,
    },

    mutations: {
        setClaims(state, expenseClaimsList) {
            state.claims = expenseClaimsList
            state.currentClaimId = null
        },

        setCurrentClaimId(state, currentClaimId) {
            state.currentClaimId = currentClaimId
        }
    },

    getters: {
        currentClaimId: state => {
            const claims = state.claims
            const currentClaimId = state.currentClaimId

            if (!claims || !claims.size || !currentClaimId) return null;

            // confirms id in claims list
            const [_, current] = getEntryWithId(claims, currentClaimId)
            const first = claims.first()
            return current ? current.id : first.id
        },

        previousClaim: (state, getters) => {
            const claims = state.claims
            const currentClaimId = getters.currentClaimId

            if (!claims || !claims.size || !currentClaimId) return null;
        
            const [key, _] = getEntryWithId(claims, currentClaimId)
            const previousKey = key - 1
            return (previousKey >= 0) ? claims.get(previousKey).id : null
        },

        nextClaim: (state, getters) => {
            const claims = state.claims
            const currentClaimId = getters.currentClaimId

            if (!claims || !claims.size || !currentClaimId) return null;
        
            const [key, _] = getEntryWithId(claims, currentClaimId)
            const nextKey = key + 1
            return (nextKey < claims.size) ? claims.get(nextKey).id : null
        },

        firstClaim: (state, getters) => {
            const claims = state.claims
            const currentClaimId = getters.currentClaimId

            if (!claims || !claims.size || !currentClaimId) return null;

            return (currentClaimId !== claims.first().id) ? claims.first().id : null
        },

        lastClaim: (state, getters) => {
            const claims = state.claims
            const currentClaimId = getters.currentClaimId

            if (!claims || !claims.size || !currentClaimId) return null;

            return (currentClaimId !== claims.last().id) ? claims.last().id : null
        }
    },

    actions: {

        async fetchExpenseClaimsForSessionReport({}, sessionReportId) {
            return (await axios.get(`/api/expense-claims`, { params: {session_id: sessionReportId} } )).data
        },

        async fetchExpenseClaim({}, expenseClaimId) {
            return (await axios.get(`/api/expense-claims/${expenseClaimId}`)).data
        },

        async updateExpenseClaimStatus({}, {id, status, financeCode=null}) {
            return (
                await axios.put(`/api/expense-claims/${id}/status`,  { status, finance_code: financeCode })
            ).data
        }
    },

    modules: {
        lookups: {
            namespaced: true,

            state: {
                // lookups
                statusLookup: List(),
            },
            mutations: {
                setStatusLookup(state, statusLookup) {
                    state.statusLookup = statusLookup
                },
            },
            actions: {
                async initialiseStatusLookup({commit}) {
                    const fetchStatuses = async () => (await axios.get(`/api/expense-claims/statuses`)).data
                    
                    const statusLookup = List(await fetchStatuses())
                    
                    commit('setStatusLookup', statusLookup)
                }
            }
        },

        search: {
            namespaced: true,
        
            actions: {
                async search({commit, dispatch}, query) {
                    const results = List(await dispatch('fetchExpenseClaims', query))
                    commit('expenses/setClaims', results, {root: true})
                },

                async fetchExpenseClaims({}, params) {
                    return (await axios.get(`/api/expense-claims`, { params: params })).data
                },

                async fetchExpenseClaimsExport({}, params) {
                    const response = await axios({
                        method: 'GET',
                        url: '/api/expense-claims/export',
                        params: params,
                        responseType: 'blob',
                    })
        
                    const data = _.get(response, 'data')
        
                    const filename = _.has(response, 'headers.content-disposition') 
                        ? contentDisposition.parse(_.get(response, 'headers.content-disposition')).parameters.filename
                        : null
        
                    return {data, filename}
                }
            }
        }
    }
}

const getEntryWithId = (list, id) => list.findEntry(item => item.id === id)

export default module