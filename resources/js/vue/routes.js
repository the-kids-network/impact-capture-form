import NotFoundPage from './pages/not-found'

import SessionReportsIndexPage from './pages/session-reports/index'
import SessionReportsSearchPage from './pages/session-reports/search'
import SessionReportsWorkflowPage from './pages/session-reports/workflow'
import SessionReportManagePage from './pages/session-reports/manage'

import ExpensesIndexPage from './pages/expenses/index'
import ExpensesSearchPage from './pages/expenses/search'
import ExpensesWorkflowPage from './pages/expenses/workflow'
import ExpenseClaimManagePage from './pages/expenses/manage-top'

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
        path: '/expenses', 
        component: ExpensesIndexPage, 
        props: true,
        children: [
            {
                path: '',
                redirect: {name: 'expenses-search'},
                props: true,
            },
            {
                path: 'search',
                name: 'expenses-search',
                component: ExpensesSearchPage,
                props: true,
            },
            {
                path: 'workflow',
                name: 'expenses-workflow',
                component: ExpensesWorkflowPage,
                props: true,
            },
            {
                path: ':expenseClaimId(\\d+)',
                name: 'expense-claims-view-one',
                component: ExpenseClaimManagePage,
                props: true,
            },
            {
                path: '*',
                redirect: {name: 'expenses-search'},
            }
        ]
    },
    {
        path: '*',
        component: NotFoundPage
    }
]

export default routes