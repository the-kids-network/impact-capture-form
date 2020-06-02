describe('Login', () => {
    beforeEach(() => {
        cy.visit('/')
        cy.contains('Login').click()
    })

    describe('Successful log in', () => {
        beforeEach(() => {
            
        })

        it('Logs in admin user', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.admin.email)
                cy.get('input[id=password]').type(users.admin.password)
            }),
            cy.get('button').contains('Login').click()
            
            cy.url().should('contain', '/home')
        })

        it('Logs in manager user', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.manager.email)
                cy.get('input[id=password]').type(users.manager.password)
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/home')
        })

        it('Logs in mentor user', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.mentor.email)
                cy.get('input[id=password]').type(users.mentor.password)
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/home')
        })
    })

    describe('Unsuccessful login', () => {
        it('Fails for incorrect password', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.admin.email)
                cy.get('input[id=password]').type("incorrect")
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/login')
            cy.get('.alert').should('contain', 'These credentials do not match our records')
        })
        it('Fails for email not supplied', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=password]').type("incorrect")
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/login')
            cy.get('.alert').should('contain', 'The email field is required')
        })
        it('Fails for password not supplied', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.admin.email)
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/login')
            cy.get('.alert').should('contain', 'The password field is required')
        })
    })
})