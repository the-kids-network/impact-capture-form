describe('View session reports', () => {
    describe('For mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })

            // session reports page
            cy.visit('/report')
            cy.url().should('contain', '/report')
        })

        it('Displays reports for mentor only', () => {
            cy.get('.session-report.item ').should('have.length', 1)
            cy.get('.session-report.item .mentor-name').should('contain', 'mentor-1')
        })
    })

    describe('For a manager', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })
           
            // session reports page
            cy.visit('/report')
            cy.url().should('contain', '/report')
        })

        it('Displays reports for managed mentors', () => {
            cy.get('.session-report.item').should('have.length', 2)
            cy.get('.session-report.item .mentor-name').each(item =>
                expect(item.text()).to.be.oneOf(['mentor-1', 'mentor-2'])
            )        
        })
    })

    describe('For an admin', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.admin)
            })
        
            // session reports page
            cy.visit('/report')
            cy.url().should('contain', '/report')
        })

        it('Displays reports for all mentors', () => {
            cy.get('.session-report.item').should('have.length', 4)            
            cy.get('.session-report.item .mentor-name').each(item =>
                expect(item.text()).to.be.oneOf(['mentor-1', 'mentor-2', 'mentor-3', 'mentor-4'])
            )
        })
    })
})

describe('Session report details', () => {
    describe('View as a mentor', ()=> {
        beforeEach(() => {
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })
        })
        
        it("Displays report owned by them", () => {
            cy.visit('/report/1')

            cy.url().should('contain', '/report/1')
            cy.get('.session-id .value').should('contain', '1')
        })


        it('Does not allow modify / delete', () => {
            cy.visit('/report/1')
            cy.get('.modify-session-report').should('not.exist')

            cy.visit('/report/1/edit', {failOnStatusCode: false})
            cy.get('body .message').should('contain', 'Unauthorized')
        })

        it("Errors for report not owned by them", () => {
            cy.visit('/report/2', {failOnStatusCode: false})

            cy.get('body .message').should('contain', 'Unauthorized')
        })
    })

    describe('View as a manager', ()=> {
        beforeEach(() => {
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })
        })
        
        it("Displays report for mentor managed by them", () => {
            cy.visit('/report/1')

            cy.url().should('contain', '/report/1')
            cy.get('.session-id .value').should('contain', '1')
        })

        it('Does allow modify / delete', () => {
            cy.visit('/report/1')
            cy.get('.modify-session-report').should('exist')

            cy.visit('/report/1/edit', {failOnStatusCode: false})
            cy.get('body .message').should('not.contain', 'Unauthorized')
        })

        it("Errors for report from mentor not managed by them", () => {
            cy.visit('/report/3', {failOnStatusCode: false})

            cy.get('body .message').should('contain', 'Unauthorized')
        })
    })

    describe('View as an admin', ()=> {
        beforeEach(() => {
            cy.fixture('users').then((users) => {
                cy.login(users.admin)
            })
        })
        
        it("Displays report from any mentor", () => {
            cy.visit('/report/1')
            cy.url().should('contain', '/report/1')
            cy.get('.session-id .value').should('contain', '1')

            cy.visit('/report/2')
            cy.url().should('contain', '/report/2')
            cy.get('.session-id .value').should('contain', '2')
        })

        it('Does allow modify / delete', () => {
            cy.visit('/report/1')
            cy.get('.modify-session-report').should('exist')

            cy.visit('/report/1/edit', {failOnStatusCode: false})
            cy.get('body .message').should('not.contain', 'Unauthorized')
        })
    })
})
