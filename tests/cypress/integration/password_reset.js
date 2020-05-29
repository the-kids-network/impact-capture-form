describe('Password reset', () => {
    beforeEach(() => {
        cy.visit('/')
        cy.contains('Login').click()
        cy.contains(/Forgot your password/i).click()
    })

    it('Succeeds for known email', () => {
        cy.fixture('users').then((users) => {
            cy.get('input[id=emailInput]').type(users.admin.email)
        }),
        cy.get('button').contains(/Password Reset/i).click()
        
        cy.get('.alert-success').should('contain', 'We have e-mailed your password reset link!')
    })

    it('Fails for unknown email', () => {
        cy.fixture('users').then((users) => {
            cy.get('input[id=emailInput]').type("a@b.com")
        }),
        cy.get('button').contains(/Password Reset/i).click()
        
        cy.get('.invalid-email')
            .should('be.visible') 
            .should('contain', 'can\'t find a user with that e-mail address.')
    })
})