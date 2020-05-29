describe('Mentor portal index', () => {
    beforeEach(() => {
        cy.visit('/')
        cy.contains('Login').click()
        cy.fixture('users').then((users) => {
            cy.get('input[id=email]').type(users.mentor.email)
            cy.get('input[id=password]').type(users.mentor.password)
        })
        cy.get('.btn').contains('Login').click()
        cy.url().should('contain', '/home')
    })

    it('Has correct number of links', () => {
        cy.get('.link-panel').should('have.length', 6)
    })

    it('Can navigate to submit a session report', () => {
        cy.get('.btn').contains(/Submit A Session Report/i).click()

        cy.get('.session-report.new').should('exist')
    })

    it('Can navigate to view session reports', () => {
        cy.get('.btn').contains(/View Reports/i).click()

        cy.get('.session-report.list').should('exist')
    })

    it('Can navigate to submit an expense claim', () => {
        cy.get('.btn').contains(/Submit An Expense Claim/i).click()

        cy.get('.expense-claim.new').should('exist')
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
        cy.visit('/')
        cy.contains('Login').click()
        cy.fixture('users').then((users) => {
            cy.get('input[id=email]').type(users.manager.email)
            cy.get('input[id=password]').type(users.manager.password)
        })
        cy.get('.btn').contains('Login').click()
        cy.url().should('contain', '/home')
    })

    it('Has correct number of links', () => {
        cy.get('.link-panel').should('have.length', 7)
    })

    it('Can navigate to view session reports', () => {
        cy.get('.btn').contains(/Session Reports/i).click()

        cy.get('.session-report.list').should('exist')
    })

    it('Can navigate to expense claims', () => {
        cy.get('.btn').contains(/Expense Claims/i).click()

        cy.get('.expense-claim.list').should('exist')
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
        cy.visit('/')
        cy.contains('Login').click()
        cy.fixture('users').then((users) => {
            cy.get('input[id=email]').type(users.admin.email)
            cy.get('input[id=password]').type(users.admin.password)
        })
        cy.get('.btn').contains('Login').click()
        cy.url().should('contain', '/home')
    })

    it('Has correct number of links', () => {
        cy.get('.link-panel').should('have.length', 17)
    })

    it('Can navigate to view session reports', () => {
        cy.get('.btn').contains(/Session Reports/i).click()

        cy.get('.session-report.list').should('exist')
    })

    it('Can navigate to expense claims', () => {
        cy.get('.btn').contains(/Expense Claims/i).click()

        cy.get('.expense-claim.list').should('exist')
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

    it('Can navigate to process expenses', () => {
        cy.get('.btn').contains(/Process Expense/i).click()

        cy.get('.expense-claim.processing').should('exist')
    })

    it('Can navigate to download expenses', () => {
        cy.get('.btn').contains(/Download Expense/i).click()

        cy.get('.expense-claim.export').should('exist')
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