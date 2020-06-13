describe('User registration', () => {
    beforeEach(() => {
        // Login
        cy.fixture('users').then((users) => {
            cy.login(users.admin)
        })
    })
    
    describe('For mentor (authenticated)', () => {
        beforeEach(() => {
            cy.visit('/register')

            // Fill in form
            cy.get('input[id=nameInput]').type('Jane New')
            cy.get('input[id=emailInput]').type('janenew@example.com')
            cy.get('input[id=passwordInput]').type('secret')
            cy.get('input[id=passwordConfirmInput]').type('secret')
        })

        it('Succeeds for valid information', () => {
            cy.get('.btn').contains(/Register/i).click()

            // Assert success message
            cy.get('.alert-success').should('contain', 'Registration was successful')

            // Login as user
            cy.visit('/logout')
            cy.login({
                "email": "janenew@example.com",
                "password": "secret"
            })
            cy.visit('/settings')
            cy.get('.settings .contact-info input[id=nameInput]').should('have.value', 'Jane New')
        })

        it('Fails if user email already exists', () => {
            // Fill in form
            cy.get('input[id=emailInput]').clear().type('mentor-1@example.com')
            cy.get('.btn').contains(/Register/i).click()

            // Assert failure message
            cy.get('.invalid-email')
                .should('be.visible') 
                .should('contain', 'The email has already been taken')
        })

        it('Fails if passwords not match', () => {
            cy.get('input[id=passwordConfirmInput]').clear().type('doesnotmatch')
            cy.get('.btn').contains(/Register/i).click()

            // Assert failure message
            cy.get('.invalid-password')
                .should('be.visible') 
                .should('contain', 'The password confirmation does not match')
        })

        it('Fails if password does not meet minimum requirements', () => {
            cy.get('input[id=passwordInput]').clear().type('a')
            cy.get('input[id=passwordConfirmInput]').clear().type('a')
            cy.get('.btn').contains(/Register/i).click()

            // Assert failure message
            cy.get('.invalid-password')
                .should('be.visible') 
                .should('contain', 'password must be at least 6 characters')
        })
    })

    describe('For mentee (non-authenticated)', () => {
        beforeEach(() => {
            cy.visit('/mentees')
        })

        it('Creates new mentee', () => {
            // Fill in form
            cy.get('.mentee-add input[id=firstNameInput]').type("G")
            cy.get('.mentee-add input[id=lastNameInput]').type("B")
            cy.get('.mentee-add .btn').contains(/Add Mentee/i).click()

            // Assert
            cy.get('.alert-success').should('contain', 'Mentee Added')
            cy.get('.mentee-existing .mentee .name').contains("G B").should('exist')
        })
    })
})