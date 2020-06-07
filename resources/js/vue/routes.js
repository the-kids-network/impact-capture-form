import NotFoundPage from './pages/not-found'
import SessionReportsIndexPage from './pages/session-reports/index'
import SessionReportsSearchPage from './pages/session-reports/search'
import SessionReportsWorkflowPage from './pages/session-reports/workflow'
import SessionReportManagePage from './pages/session-reports/manage'

const routes = [
    { 
        path: '/session-reports', 
        component: SessionReportsIndexPage, 
        props: true,
        children: [
            {
                path: '',
                redirect: {name: 'session-reports-search'},
                props: true,
            },
            {
                path: 'search',
                name: 'session-reports-search',
                component: SessionReportsSearchPage,
                props: true,
            },
            {
                path: 'workflow',
                name: 'session-reports-workflow',
                component: SessionReportsWorkflowPage,
                props: true,
            },
            {
                path: ':sessionReportId(\\d+)',
                name: 'session-reports-view-one',
                component: SessionReportManagePage,
                props: true,
            },
            {
                path: '*',
                redirect: {name: 'session-reports-search'},
            }
        ]
    },
    {
        path: '*',
        component: NotFoundPage
    }
]

export default routes