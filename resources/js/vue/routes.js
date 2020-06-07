import SessionReportsSearchTop from './components/session-reports/search-top'
import SessionReportsWorkflowTop from './components/session-reports/workflow-top'

const routes = [
    { 
        path: '/', 
        name: 'root',
        components: {
            'session-reports': SessionReportsSearchTop,
        },
        props: { 'session-reports': true }
    },
    { 
        path: '/search', 
        name: 'search',
        components: {
            'session-reports': SessionReportsSearchTop,
        },
        props: { 'session-reports': true }
    },
    { 
        path: '/workflow', 
        name: 'workflow',
        components: {
            'session-reports': SessionReportsWorkflowTop,
        },
        props: { 'session-reports': true }

    }
]

export default routes