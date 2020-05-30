describe('Expense claim submission', () => {
    describe('For mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })

            // Submit expense claim page
            cy.visit('/expense-claim/new')
            cy.url().should('contain', '/expense-claim/new')
        })

        it('Succeeds for full data', () => {
            cy.get('#relatedSessionSelect').select('1')

            // Fill in form
            cy.get('#expense-form-table tr').eq(1).find('.expense-date').type('28-05-2020')
            cy.get('.card-body').click({force: true})
            cy.get('#expense-form-table tr').eq(1).find('.expense-description').type('Cinema Ticket')
            cy.get('#expense-form-table tr').eq(1).find('.expense-amount').type('1.50')

            cy.contains(/Add Row/i).click()

            cy.get('#expense-form-table tr').eq(2).find('.expense-date').type('28-05-2020')
            cy.get('.card-body').click({force: true})
            cy.get('#expense-form-table tr').eq(2).find('.expense-description').type('Tube Ticket')
            cy.get('#expense-form-table tr').eq(2).find('.expense-amount').type('5.25')

            cy.get('#receiptsInput').attachFile('picture.jpeg');

            // Submit form
            cy.get('.btn').contains('Submit').click()

            // Verify saved expense claim
            cy.get('.alert-success').should('contain', 'Expense Claim Submitted')
            cy.get('.mentor-name .value').should('contain', 'mentor-1')
            cy.get('.status .value').should('contain', 'pending')
            cy.get('.status .value').should('contain', 'pending')
            cy.get('#expenses-table tr').eq(1).find('.expense-date').should('contain', 'May 28, 2020')
            cy.get('#expenses-table tr').eq(1).find('.expense-description').should('contain', 'Cinema Ticket')
            cy.get('#expenses-table tr').eq(1).find('.expense-amount').should('contain', '1.5')
            cy.get('#expenses-table tr').eq(2).find('.expense-date').should('contain', 'May 28, 2020')
            cy.get('#expenses-table tr').eq(2).find('.expense-description').should('contain', 'Tube Ticket')
            cy.get('#expenses-table tr').eq(2).find('.expense-amount').should('contain', '5.25')
            cy.get('.receipt-link').should('have.length', 1)
        })
    })
})

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
            cy.get('.expense-claim.item').should('have.length', 2)
            cy.get('.expense-claim.item .mentor-name').should('contain', 'mentor-1')
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
            cy.visit('/expense-claim/3', {failOnStatusCode: false})

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
