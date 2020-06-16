import _ from 'lodash'
import { List } from 'immutable'

const module = {
    namespaced: true,
    state: {
        list: List(),
        current: null,
    },
    mutations: {
        setSessionReports(state, reportsList) {
            state.list = reportsList
            state.current = null
        },

        setCurrentSessionReportId(state, currentSessionReportIdId) {
            state.current = currentSessionReportIdId
        }
    },
    getters: {
        currentSessionReportId: state => {
            const sessionReports = state.list
            const currentSessionReportId = state.current

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;

            // confirms id in session list
            const [_, current] = getEntryWithId(sessionReports, currentSessionReportId)
            const first = sessionReports.first()
            return current ? current.id : first.id
        },

        previousSessionReport: (state, getters) => {
            const sessionReports = state.list
            const currentSessionReportId = getters.currentSessionReportId

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;
        
            const [key, _] = getEntryWithId(sessionReports, currentSessionReportId)
            const previousKey = key - 1
            return (previousKey >= 0) ? sessionReports.get(previousKey).id : null
        },

        nextSessionReport: (state, getters) => {
            const sessionReports = state.list
            const currentSessionReportId = getters.currentSessionReportId

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;
        
            const [key, _] = getEntryWithId(sessionReports, currentSessionReportId)
            const nextKey = key + 1
            return (nextKey < sessionReports.size) ? sessionReports.get(nextKey).id : null
        },

        firstSessionReport: (state, getters) => {
            const sessionReports = state.list
            const currentSessionReportId = getters.currentSessionReportId

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;

            return (currentSessionReportId !== sessionReports.first().id) ? sessionReports.first().id : null
        },

        lastSessionReport: (state, getters) => {
            const sessionReports = state.list
            const currentSessionReportId = getters.currentSessionReportId

            if (!sessionReports || !sessionReports.size || !currentSessionReportId) return null;

            return (currentSessionReportId !== sessionReports.last().id) ? sessionReports.last().id : null
        }
    }
}

const getEntryWithId = (list, id) => list.findEntry(item => item.id === id)

export default module