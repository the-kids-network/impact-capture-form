describe('Login', () => {
    beforeEach(() => {
        cy.visit('http://127.0.0.1:8000/')
        cy.contains('Login').click()
    })

    describe('Successfully logs in', () => {
        beforeEach(() => {
            
        })

        it('For admin user', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.admin.email)
                cy.get('input[id=password]').type(users.admin.password)
            }),
            cy.get('button').contains('Login').click()
            
            cy.url().should('contain', '/home')
        })

        it('For manager user', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.manager.email)
                cy.get('input[id=password]').type(users.manager.password)
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/home')
        })

        it('For mentor user', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.mentor.email)
                cy.get('input[id=password]').type(users.mentor.password)
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/home')
        })
    })

    describe('Fails with alert', () => {
        it('For incorrect password', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.admin.email)
                cy.get('input[id=password]').type("incorrect")
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/login')
            cy.get('.alert').should('contain', 'These credentials do not match our records')
        })
        it('For email not supplied', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=password]').type("incorrect")
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/login')
            cy.get('.alert').should('contain', 'The email field is required')
        })
        it('For password not supplied', () => {
            cy.fixture('users').then((users) => {
                cy.get('input[id=email]').type(users.admin.email)
            }),
            cy.get('button').contains('Login').click()

            cy.url().should('contain', '/login')
            cy.get('.alert').should('contain', 'The password field is required')
        })
    })
    
})