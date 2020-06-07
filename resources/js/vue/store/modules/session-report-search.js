import _ from 'lodash'
import { List } from 'immutable'

const module = {
    state: {
        list: List(),
        current: null,
    },
    mutations: {
        setSessionReports(state, reportsList) {
            state.list = List(reportsList)
            state.current = null
        },

        setCurrentSessionReport(state, currentSessionReportId) {
            state.current = currentSessionReportId
        }
    },
    getters: {
        currentSessionReport: state => {
            const sessionReports = state.list
            const currentSessionReport = state.current

            if (!sessionReports || !sessionReports.size || !currentSessionReport) return null;

            // confirms id in session list
            const [_, current] = getEntryWithId(sessionReports, currentSessionReport)
            const first = sessionReports.first()
            return current ? current.id : first.id
        },

        previousSessionReport: (state, getters) => {
            const sessionReports = state.list
            const currentSessionReport = getters.currentSessionReport

            if (!sessionReports || !sessionReports.size || !currentSessionReport) return null;
        
            const [key, _] = getEntryWithId(sessionReports, currentSessionReport)
            const previousKey = key - 1
            return (previousKey >= 0) ? sessionReports.get(previousKey).id : null
        },

        nextSessionReport: (state, getters) => {
            const sessionReports = state.list
            const currentSessionReport = getters.currentSessionReport

            if (!sessionReports || !sessionReports.size || !currentSessionReport) return null;
        
            const [key, _] = getEntryWithId(sessionReports, currentSessionReport)
            const nextKey = key + 1
            return (nextKey < sessionReports.size) ? sessionReports.get(nextKey).id : null
        }
    }
}

const getEntryWithId = (list, id) => list.findEntry(item => item.id === id)

export default module