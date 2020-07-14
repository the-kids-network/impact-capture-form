describe('View expense claims', () => {
    describe('For mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })

            // expense claims page
            cy.visit('/expense-claim')
            cy.url().should('contain', '/expense-claim')
        })

        it('Displays claims for mentor only', () => {
            cy.get('.expense-claim.item').should('have.length', 2)
            cy.get('.expense-claim.item .mentor-name').should('contain', 'mentor-1')
        })
    })

    describe('For a manager', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })
           
            // expense claims page
            cy.visit('/expense-claim')
            cy.url().should('contain', '/expense-claim')
        })

        it('Displays claims for managed mentors', () => {
            cy.get('.expense-claim.item').should('have.length', 3)
            cy.get('.expense-claim.item .mentor-name').each(item =>
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
        
            // expense claims page
            cy.visit('/expense-claim')
            cy.url().should('contain', '/expense-claim')
        })

        it('Displays claims for all mentors', () => {
            cy.get('.expense-claim.item').should('have.length', 4)
            cy.get('.expense-claim.item .mentor-name').each(item =>
                expect(item.text()).to.be.oneOf(['mentor-1', 'mentor-2', 'mentor-3', 'mentor-4'])
            )
        })
    })
})

describe('Expense claim details', () => {
    describe('View as a mentor', ()=> {
        beforeEach(() => {
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })
        })
        
        it("Displays claims owned by them", () => {
            cy.visit('/expense-claim/1')

            cy.url().should('contain', '/expense-claim/1')
            cy.get('.claim-id .value').should('contain', '1')
        })

        it("Errors for claim not owned by them", () => {
            cy.visit('/expense-claim/3', {failOnStatusCode: false})

            cy.get('body .message').should('contain', 'Unauthorized')
        })
    })

    describe('View as a manager', ()=> {
        beforeEach(() => {
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })
        })
        
        it("Displays claim for mentor managed by them", () => {
            cy.visit('/expense-claim/1')

            cy.url().should('contain', '/expense-claim/1')
            cy.get('.claim-id .value').should('contain', '1')
        })

        it("Errors for claim from mentor not managed by them", () => {
            cy.visit('/expense-claim/4', {failOnStatusCode: false})

            cy.get('body .message').should('contain', 'Unauthorized')
        })
    })

    describe('View as an admin', ()=> {
        beforeEach(() => {
            cy.fixture('users').then((users) => {
                cy.login(users.admin)
            })
        })
        
        it("Displays claim from any mentor", () => {
            cy.visit('/expense-claim/1')
            cy.url().should('contain', '/expense-claim/1')
            cy.get('.claim-id .value').should('contain', '1')

            cy.visit('/expense-claim/3')
            cy.url().should('contain', '/expense-claim/3')
            cy.get('.claim-id .value').should('contain', '3')
        })
    })
})
