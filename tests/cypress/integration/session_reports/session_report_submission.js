describe('Session report submission', () => {
    describe('For mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })

            // submit session report page
            cy.visit('/report/new')
            cy.url().should('contain', '/report/new')
        })

        it('Succeeds for full data', () => {
            // Fill in form
            cy.get('select[id=menteeSelect]').select('mentee 1')
            cy.get('input[id=sessionDateInput]').type('28-05-2020')
            cy.get('.card-body').click({force: true})
            cy.get('select[id=ratingSelect]').select('Good')
            cy.get('input[id=lengthInput]').type('0.5')
            cy.get('select[id=activityTypeSelect]').select('Park')
            cy.get('input[id=locationInput]').type('Regent\'s Park')
            cy.get('select[id=safeguardingSelect]').select('2')
            cy.get('select[id=emotionalStateSelect]').select('Sad')
            cy.get('textarea[id=meetingDetailsInput]').type('Went for a walk in the park')

            const dateSevenDaysForward = Cypress.moment().add(7, 'd').format('DD-MM-YYYY')
            cy.get('input[id=nextSessionDateInput]').type(dateSevenDaysForward)
            cy.get('.card-body').click({force: true})
            cy.get('input[id=nextSessionLocationInput]').type('Hyde Park')

            cy.get('input[id=leaveStartDateInput]').type('29-05-2020')
            cy.get('.card-body').click({force: true})
            cy.get('input[id=leaveEndDateInput]').type('31-05-2020')
            cy.get('.card-body').click({force: true})
            cy.get('input[id=leaveDescriptionInput]').type('Holiday')

            // Submit report
            cy.get('.btn').contains('Submit').click()

            // Check on report page with success message
            cy.get('.alert-success').should('contain', 'Report Created')
          
            // Check details of saved report
            cy.get('.mentor-name .value').should('contain', 'mentor-1')
            cy.get('.mentee-name .value').should('contain', 'mentee 1')
            cy.get('.session-date .value').should('contain', 'May 28')
            cy.get('.session-rating .value').should('contain', 'Good')
            cy.get('.session-length .value').should('contain', '0.5')
            cy.get('.activity-type .value').should('contain', 'Park')
            cy.get('.session-location .value').should('contain', 'Regent\'s Park')
            cy.get('.safeguarding-concern .value').should('contain', 'Yes - Mild')
            cy.get('.mentee-emotional-state .value').should('contain', 'Sad')
            cy.get('.meeting-details .value').should('contain', 'walk in the park')
        })
    })
})