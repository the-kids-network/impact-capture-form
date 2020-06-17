import _ from 'lodash'
import { List } from 'immutable'
import contentDisposition from 'content-disposition'

const module = {
    namespaced: true,

    state: {
        reports: List(),
        currentReportId: null,
    },

    mutations: {
        setSessionReports(state, reportsList) {
            state.reports = reportsList
            state.currentReportId = null
        },

        setCurrentSessionReportId(state, currentReportIdId) {
            state.currentReportId = currentReportIdId
        }
    },

    getters: {
        currentSessionReportId: state => {
            const sessionReports = state.reports
            const currentSessionReportId = state.currentReportId

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;

            // confirms id in session list
            const [_, current] = getEntryWithId(sessionReports, currentSessionReportId)
            const first = sessionReports.first()
            return current ? current.id : first.id
        },

        previousSessionReport: (state, getters) => {
            const sessionReports = state.reports
            const currentSessionReportId = getters.currentSessionReportId

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;
        
            const [key, _] = getEntryWithId(sessionReports, currentSessionReportId)
            const previousKey = key - 1
            return (previousKey >= 0) ? sessionReports.get(previousKey).id : null
        },

        nextSessionReport: (state, getters) => {
            const sessionReports = state.reports
            const currentSessionReportId = getters.currentSessionReportId

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;
        
            const [key, _] = getEntryWithId(sessionReports, currentSessionReportId)
            const nextKey = key + 1
            return (nextKey < sessionReports.size) ? sessionReports.get(nextKey).id : null
        },

        firstSessionReport: (state, getters) => {
            const sessionReports = state.reports
            const currentSessionReportId = getters.currentSessionReportId

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;

            return (currentSessionReportId !== sessionReports.first().id) ? sessionReports.first().id : null
        },

        lastSessionReport: (state, getters) => {
            const sessionReports = state.reports
            const currentSessionReportId = getters.currentSessionReportId

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;

            return (currentSessionReportId !== sessionReports.last().id) ? sessionReports.last().id : null
        }
    },

    actions: {
        async deleteSessionReport({}, id) {
            return (await axios.delete(`/api/session-reports/${id}`)).data
        },

        async updateSessionReport({}, {id, reportData}) {
            return (await axios.put(`/api/session-reports/${id}`, reportData)).data
        },

        async fetchSessionReport({}, id) {
            return (await axios.get(`/api/session-reports/${id}`)).data
        },
    },

    modules: {
        lookups: {
            namespaced: true,

            state: {
                // lookups
                mentorsLookup: List(),
                safeguardingLookup: List(),
                sessionRatingsLookup: List(),
                activityTypesLookup: List(),
                emotionalStatesLookup: List()
            },
            mutations: {
                setMentorsLookup(state, mentorsLookup) {
                    state.mentorsLookup = mentorsLookup
                },
                setSafeguardingLookup(state, safeguardingLookup) {
                    state.safeguardingLookup = safeguardingLookup
                },
                setSessionRatingsLookup(state, sessionRatingsLookup) {
                    state.sessionRatingsLookup = sessionRatingsLookup
                },
                setActivityTypesLookup(state, lookup) {
                    state.activityTypesLookup = lookup
                },
                setEmotionalStatesLookup(state, lookup) {
                    state.emotionalStatesLookup = lookup
                }
            },
            actions: {
                async initialiseMentorsLookup({commit}) {
                    const fetchMentors = async () => (await axios.get(`/api/users`, { params: {role: 'mentor'} })).data
                    
                    const mentorsLookup = List(await fetchMentors())
                    
                    commit('setMentorsLookup', mentorsLookup)
                },
        
                async initialiseSafeguardingLookup({commit}) {
                    const fetchSafeguardingData = async () => (await axios.get(`/api/safeguarding-options`)).data
                    
                    const safeguardingLookup = List(await fetchSafeguardingData())
                    
                    commit('setSafeguardingLookup', safeguardingLookup)
                },
        
                async initialiseSessionRatingsLookup({commit}) {
                    const fetchSessionRatings = async () => (await axios.get(`/api/session-ratings`)).data

                    const sessionRatingsLookup = List(await fetchSessionRatings())
                    
                    commit('setSessionRatingsLookup', sessionRatingsLookup)
                },

                async initialiseActivityTypesLookup({commit}) {
                    const fetchActivityTypes = async () => (await axios.get('/api/activity-types', { params: {trashed: true} })).data

                    const activityTypesLookup = List(await fetchActivityTypes())
                    
                    commit('setActivityTypesLookup', activityTypesLookup)
                },
        
                async initialiseEmotionalStatesLookup({commit}) {
                    const fetchEmotionalStates = async () => (await axios.get('/api/emotional-states', { params: {trashed: true} })).data

                    const emotionalStatesLookup = List(await fetchEmotionalStates())
                    
                    commit('setEmotionalStatesLookup', emotionalStatesLookup)
                },
            }
        },

        search: {
            namespaced: true,
        
            actions: {
                async search({commit, dispatch}, query) {
                    const results = List(await dispatch('fetchSessionReports', query))
                    commit('sessionReports/setSessionReports', results, {root: true})
                },

                async fetchSessionReports({}, params) {
                    return (await axios.get(`/api/session-reports`, { params: params })).data
                },

                async fetchSessionReportsExport({}, params) {
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
            }
        }
    }
}

const getEntryWithId = (list, id) => list.findEntry(item => item.id === id)

export default module