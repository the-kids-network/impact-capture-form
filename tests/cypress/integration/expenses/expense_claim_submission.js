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
