describe('Mentor portal index', () => {
    beforeEach(() => {
        cy.fixture('users').then((users) => {
            cy.login(users.mentor)
        })

        cy.visit('/home')
    })

    it('Has correct number of links', () => {
        cy.get('.link-panel').should('have.length', 7)
    })

    it('Can navigate to submit a session report', () => {
        cy.get('.btn').contains(/Submit A Session Report/i).click()

        cy.get('.session-report.new').should('exist')
    })

    it('Can navigate to view session reports', () => {
        cy.get('.btn').contains(/View Reports/i).click()

        cy.get('.session-report-list').should('exist')
    })

    it('Can navigate to submit an expense claim', () => {
        cy.get('.btn').contains(/Submit An Expense Claim/i).click()

        cy.get('.expense-claim.new').should('exist')
    })

    it('Can navigate to view expense claims', () => {
        cy.get('.btn').contains(/View Expense Claims/i).click()

        cy.get('.expense-claim-list').should('exist')
    })

    it('Can navigate to calendar', () => {
        cy.get('.btn').contains(/Calendar/i).click()

        cy.get('.calendar').should('exist')
    })

    it('Can navigate to change next planned session', () => {
        cy.get('.btn').contains(/Change Next Planned Session/i).click()

        cy.get('.planned-session.show').should('exist')
    })

    it('Can navigate to documents', () => {
        cy.get('.btn').contains(/Documents/i).click()

        cy.get('.documents-management').should('exist')
    })
})

describe('Manager portal index', () => {
    beforeEach(() => {
        cy.fixture('users').then((users) => {
            cy.login(users.manager)
        })

        cy.visit('/home')
    })

    it('Has correct number of links', () => {
        cy.get('.link-panel').should('have.length', 8)
    })

    it('Can navigate to view session reports (v2)', () => {
        cy.get('.v2-session-reports').click()

        cy.get('.session-report-search').should('exist')
    })

    it('Can navigate to expenses (v1)', () => {
        cy.get('.btn').contains(/Expenses V1/i).click()

        cy.get('.expense-claim.list').should('exist')
    })

    it('Can navigate to expenses (vv)', () => {
        cy.get('.btn').contains(/Expenses V2/i).click()

        cy.get('.expense-claim-search').should('exist')
    })

    it('Can navigate to calendar', () => {
        cy.get('.btn').contains(/Calendar/i).click()

        cy.get('.calendar').should('exist')
    })

    it('Can navigate to documents upload', () => {
        cy.get('.btn').contains(/Upload/i).click()

        cy.get('.documents-upload').should('exist')
    })

    it('Can navigate to documents management', () => {
        cy.get('.btn').contains(/Browse & Manage/i).click()

        cy.get('.documents-management').should('exist')
    })

    it('Can navigate to mentor funding', () => {
        cy.get('.btn').contains(/Funding/i).click()

        cy.get('.fundings').should('exist')
    })

    it('Can navigate to reporting', () => {
        cy.get('.btn').contains(/Reporting/i).click()

        cy.get('.mentor-reporting').should('exist')
    })
})

describe('Admin portal index', () => {
    beforeEach(() => {
        cy.fixture('users').then((users) => {
            cy.login(users.admin)
        })

        cy.visit('/home')
    })

    it('Has correct number of links', () => {
        cy.get('.link-panel').should('have.length', 17)
    })

    it('Can navigate to view session reports (v2)', () => {
        cy.get('.v2-session-reports').click()

        cy.get('.session-report-search').should('exist')
    })

    it('Can navigate to expenses (v1)', () => {
        cy.get('.btn').contains(/Expenses V1/i).click()

        cy.get('.expense-claim.list').should('exist')
    })

    it('Can navigate to expenses (v2)', () => {
        cy.get('.btn').contains(/Expenses V2/i).click()

        cy.get('.expense-claim-search').should('exist')
    })

    it('Can navigate to pending expense claims', () => {
        cy.get('.btn').contains(/Pending Expense/i).click()

        cy.get('.expense-claim-search').should('exist')
        cy.url().should('contain', 'status=Pending')
    })

    it('Can navigate to calendar', () => {
        cy.get('.btn').contains(/Calendar/i).click()

        cy.get('.calendar').should('exist')
    })

    it('Can navigate to funders', () => {
        cy.get('.btn').contains(/Funders/i).click()

        cy.get('.funder').should('exist')
    })

    it('Can navigate to mentor funding', () => {
        cy.get('.btn').contains(/Funding/i).click()

        cy.get('.fundings').should('exist')
    })

    it('Can navigate to documents upload', () => {
        cy.get('.btn').contains(/Upload/i).click()

        cy.get('.documents-upload').should('exist')
    })

    it('Can navigate to documents management', () => {
        cy.get('.btn').contains(/Browse & Manage/i).click()

        cy.get('.documents-management').should('exist')
    })
   
    it('Can navigate to reporting', () => {
        cy.get('.btn').contains(/Reporting/i).click()

        cy.get('.mentor-reporting').should('exist')
    })

    it('Can navigate to mentee management', () => {
        cy.get('.user-management .btn').contains(/Mentee/i).click()

        cy.get('.mentee-management').should('exist')
    })

    it('Can navigate to mentor user management', () => {
        cy.get('.user-management .btn').contains(/Mentor/i).click()

        cy.get('.mentor-management').should('exist')
    })

    it('Can navigate to manager user management', () => {
        cy.get('.user-management .btn').contains(/Manager/i).click()

        cy.get('.manager-management').should('exist')
    })

    it('Can navigate to admin user management', () => {
        cy.get('.user-management .btn').contains(/Admin/i).click()

        cy.get('.admin-management').should('exist')
    })

    it('Can navigate to register user', () => {
        cy.get('.user-management .btn').contains(/Register/i).click()

        cy.get('.registration').should('exist')
    })

    it('Can navigate to activity types', () => {
        cy.get('.btn').contains(/Activity Types/i).click()

        cy.get('.activity-type').should('exist')
    })

    it('Can navigate to emotional states', () => {
        cy.get('.btn').contains(/Emotional State/i).click()

        cy.get('.emotional-state').should('exist')
    })
})