const moment = require("moment")

describe('Search session reports', () => {
    describe('For mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })

            // session reports search
            cy.visit('/session-reports')
            cy.url().should('contain', '/session-reports/search')
        })

        describe('Default search', () => {
            it('Displays reports for mentor only', () => {
                cy.get('.session-report-list .items .item').should('have.length', 1)
                cy.get('.session-report-list .items .item .mentor-name').should('contain', 'mentor-1')
            })
        })

        describe('Search by ID', () => {
            it('Displays report for report owned by mentor', () => {
                cy.get('.find-by-id-toggle').click()
                cy.get('.find-by-id-form #idInput').type('1')
                cy.get('.find-by-id-form .btn.search').click()

                cy.url().should('contain', '/session-reports/1')
                cy.get('.session-report-view .table.session-report').should('exist')
            })

            it('Does not display report for non-owned report', () => {
                cy.get('.find-by-id-toggle').click()
                cy.get('.find-by-id-form #idInput').type('2')
                cy.get('.find-by-id-form .btn.search').click()

                cy.url().should('contain', '/session-reports/2')
                cy.get('.session-report-view .table.session-report').should('not.exist')
                cy.get('.session-report-view .status').should('contain', 'Problem loading session report')
            })
        })

        describe('Search by session dates', () => {
            it('Displays reports where matched', () => {
                cy.get('.session-report-search .btn').contains('Today').click()
                cy.get('.session-report-search .btn').contains('Search').click()

                cy.get('.session-report-list .items .item').should('have.length', 1)
            })

            it('Does not display reports where no match', () => {
                cy.get('.session-report-search .btn').contains('Yesterday').click()
                cy.get('.session-report-search .btn').contains('Search').click()

                cy.get('.session-report-list .items .item').should('have.length', 0)
            })
        })

        describe('Search parameters', () => {
            it('Saves search parameters as query parameters', () => {
                cy.get('.session-report-search .btn').contains('Today').click()
                cy.get('.session-report-search .btn').contains('Search').click()

                const today = Cypress.moment().format('DD-MM-YYYY')
                cy.url().should('contain', `/session-reports/search?session_date_range_start=${today}&session_date_range_end=${today}`)
            })
            it('Restores search parameters from queryparameters', () => {
                cy.visit (`/app#/session-reports/search?session_date_range_start=01-01-2019&session_date_range_end=25-07-2019`)

                cy.get('.session-report-search #sessionDateRangeStartInput').should('have.value', '01-01-2019')
                cy.get('.session-report-search #sessionDateRangeEndInput').should('have.value', '25-07-2019')
            })
        })
    })

    describe('For a manager', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })
           
             // session reports search
             cy.visit('/session-reports')
             cy.url().should('contain', '/session-reports/search')
        })

        describe('Default search', () => {
            it('Displays reports for managed mentors only', () => {
                cy.get('.session-report-list .items .item').should('have.length', 2)
                cy.get('.session-report-list .items .item .mentor-name').each(item =>
                    expect(item.text()).to.be.oneOf(['mentor-1', 'mentor-2'])
                ) 
            })
        })

        describe('Search by ID', () => {
            it('Displays report for managed mentor', () => {
                cy.get('.find-by-id-toggle').click()
                cy.get('.find-by-id-form #idInput').type('2')
                cy.get('.find-by-id-form .btn.search').click()

                cy.url().should('contain', '/session-reports/2')
                cy.get('.session-report-view .table.session-report').should('exist')
            })

            it('Does not display report for mentor not managed by user', () => {
                cy.get('.find-by-id-toggle').click()
                cy.get('.find-by-id-form #idInput').type('3')
                cy.get('.find-by-id-form .btn.search').click()

                cy.url().should('contain', '/session-reports/3')
                cy.get('.session-report-view .table.session-report').should('not.exist')
                cy.get('.session-report-view .status').should('contain', 'Problem loading session report')
            })
        })

        describe('Search by session dates', () => {
            it('Displays reports where matched', () => {
                cy.get('.session-report-search .btn').contains('Today').click()
                cy.get('.session-report-search .btn').contains('Search').click()

                cy.get('.session-report-list .items .item').should('have.length', 2)
            })

            it('Does not display reports where no match', () => {
                cy.get('.session-report-search .btn').contains('Yesterday').click()
                cy.get('.session-report-search .btn').contains('Search').click()

                cy.get('.session-report-list .items .item').should('have.length', 0)
            })
        })

        describe('Search by mentor', () => {
            it('Displays reports for mentor only', () => {
                cy.get('.session-report-search select[id=mentorSelect]').select('mentor-2')

                cy.get('.session-report-search .btn').contains('Search').click()

                cy.get('.session-report-list .items .item').should('have.length', 1)
                cy.get('.session-report-list .items .item .mentor-name').should('contain', 'mentor-2')
            })
        })

        describe('Search parameters', () => {
            it('Saves search parameters as query parameters', () => {
                cy.get('.session-report-search .btn').contains('Today').click()
                cy.get('.session-report-search select[id=mentorSelect]').select('mentor-2')
                cy.get('.session-report-search select[id=menteeSelect]').select('mentee 2')
                cy.get('.session-report-search .btn').contains('Search').click()

                const today = Cypress.moment().format('DD-MM-YYYY')
                cy.url().should('contain', `/session-reports/search?mentor_id=6&mentee_id=2&session_date_range_start=${today}&session_date_range_end=${today}`)

            })
            it('Restores search parameters from query parameters', () => {
                cy.visit (`/app#/session-reports/search?mentor_id=6&mentee_id=2&session_date_range_start=01-01-2019&session_date_range_end=25-07-2019`)

                cy.get('.session-report-search #mentorSelect').should('have.value', '6')
                cy.get('.session-report-search #menteeSelect').should('have.value', '2')
                cy.get('.session-report-search #sessionDateRangeStartInput').should('have.value', '01-01-2019')
                cy.get('.session-report-search #sessionDateRangeEndInput').should('have.value', '25-07-2019')
            })
        })
    })

    describe('For an admin', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.admin)
            })
        
            // session reports search
            cy.visit('/session-reports')
            cy.url().should('contain', '/session-reports/search')
        })

        describe('Default search', () => {
            it('Displays reports for all mentors', () => {
                cy.get('.session-report-list .items .item').should('have.length', 4)
                cy.get('.session-report-list .items .item .mentor-name').each(item =>
                    expect(item.text()).to.be.oneOf(['mentor-1', 'mentor-2', 'mentor-3', 'mentor-4'])
                ) 
            })
        })

        describe('Search by ID', () => {
            it('Displays report for mentor 1', () => {
                cy.get('.find-by-id-toggle').click()
                cy.get('.find-by-id-form #idInput').type('1')
                cy.get('.find-by-id-form .btn.search').click()

                cy.url().should('contain', '/session-reports/1')
                cy.get('.session-report-view .table.session-report').should('exist')
            })

            it('Displays report for mentor 4', () => {
                cy.get('.find-by-id-toggle').click()
                cy.get('.find-by-id-form #idInput').type('4')
                cy.get('.find-by-id-form .btn.search').click()

                cy.url().should('contain', '/session-reports/4')
                cy.get('.session-report-view .table.session-report').should('exist')

            })

            it('Does not display report that does not exist', () => {
                cy.get('.find-by-id-toggle').click()
                cy.get('.find-by-id-form #idInput').type('633')
                cy.get('.find-by-id-form .btn.search').click()

                cy.get('.session-report-view .table.session-report').should('not.exist')
                cy.get('.session-report-view .status').should('contain', 'Problem loading session report')
            })
        })

        describe('Search by session dates', () => {
            it('Displays reports where matched', () => {
                cy.get('.session-report-search .btn').contains('Today').click()
                cy.get('.session-report-search .btn').contains('Search').click()

                cy.get('.session-report-list .items .item').should('have.length', 4)
            })

            it('Does not display reports where no match', () => {
                cy.get('.session-report-search .btn').contains('Yesterday').click()
                cy.get('.session-report-search .btn').contains('Search').click()

                cy.get('.session-report-list .items .item').should('have.length', 0)
            })
        })

        describe('Search by mentor', () => {
            it('Displays reports for mentor only', () => {
                cy.get('.session-report-search select[id=mentorSelect]').select('mentor-2')

                cy.get('.session-report-search .btn').contains('Search').click()

                cy.get('.session-report-list .items .item').should('have.length', 1)
                cy.get('.session-report-list .items .item .mentor-name').should('contain', 'mentor-2')
            })
        })

        describe('Search parameters', () => {
            it('Saves search parameters as query parameters', () => {
                cy.get('.session-report-search .btn').contains('Today').click()
                cy.get('.session-report-search select[id=mentorSelect]').select('mentor-4')
                cy.get('.session-report-search select[id=menteeSelect]').select('mentee 4')
                cy.get('.session-report-search .btn').contains('Search').click()

                const today = Cypress.moment().format('DD-MM-YYYY')
                cy.url().should('contain', `/session-reports/search?mentor_id=8&mentee_id=4&session_date_range_start=${today}&session_date_range_end=${today}`)

            })
            it('Restores search parameters from query parameters', () => {
                cy.visit (`/app#/session-reports/search?mentor_id=6&mentee_id=2&session_date_range_start=01-01-2019&session_date_range_end=25-07-2019`)

                cy.get('.session-report-search #mentorSelect').should('have.value', '6')
                cy.get('.session-report-search #menteeSelect').should('have.value', '2')
                cy.get('.session-report-search #sessionDateRangeStartInput').should('have.value', '01-01-2019')
                cy.get('.session-report-search #sessionDateRangeEndInput').should('have.value', '25-07-2019')
            })
        })
    })
})

describe('Session workflow', () => {
    describe('For mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })

            // session reports search
            cy.visit('/session-reports')
            cy.url().should('contain', '/session-reports/search')
            cy.get('.session-report-list .items .item').click()
        })

        it('Shows workflow', () => {
            cy.get('.session-report-workflow').should('exist')
        })

        it('Shows selected session report in view mode', () => {
            cy.get('.session-manage-view-toggler').should('exist')
            cy.get('.session-report-view').should('exist')
            cy.get('.session-report-view .session-report .session-id .value').should('contain', '1')
        })

        it('Allows workflow to be closed', () => {
            cy.get('.close-workflow .btn').click()
            cy.url().should('contain', '/session-reports/search')
        })
    })

    describe('For manager', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })

            // session reports search
            cy.visit('/session-reports')
            cy.url().should('contain', '/session-reports/search')
            cy.get('.session-report-list .items .item').first().click()
        })

        it('Shows workflow', () => {
            cy.get('.session-report-workflow').should('exist')
        })

        it('Allows navigation', () => {
            cy.get('.navigation-buttons .next-report').click()

            cy.get('.session-report-view').should('exist')
            cy.get('.session-report-view .session-report .session-id .value').should('contain', '2')
        })

        it('Shows selected session report in view-mode', () => {
            cy.get('.session-manage-view-toggler').should('exist')
            cy.get('.session-report-view').should('exist')
            cy.get('.session-report-view .session-report .session-id .value').should('contain', '1')
        })

        it('Allows workflow to be closed', () => {
            cy.get('.close-workflow .btn').click()
            cy.url().should('contain', '/session-reports/search')
        })
    })

    describe('For admin', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.admin)
            })

            // session reports search
            cy.visit('/session-reports')
            cy.url().should('contain', '/session-reports/search')
            cy.get('.session-report-list .items .item').first().click()
        })

        it('Shows workflow', () => {
            cy.get('.session-report-workflow').should('exist')
        })

        it('Allows navigation', () => {
            cy.get('.navigation-buttons .next-report').click()

            cy.get('.session-report-view').should('exist')
            cy.get('.session-report-view .session-report .session-id .value').should('contain', '2')
        })

        it('Shows selected session report in view-mode', () => {
            cy.get('.session-manage-view-toggler').should('exist')
            cy.get('.session-report-view').should('exist')
            cy.get('.session-report-view .session-report .session-id .value').should('contain', '1')
        })

        it('Allows workflow to be closed', () => {
            cy.get('.close-workflow .btn').click()
            cy.url().should('contain', '/session-reports/search')
        })
    })
})

describe('Session manage', () => {
    
    describe('As mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })
    
            // specific session report manager
            cy.visit('app#/session-reports/1')
            cy.get('.session-manage-view-toggler').should('exist')
        })

        it ('Displays in view mode', () => {
            cy.get('.session-report-view').should('exist')
            cy.get('.session-report-view .session-report .session-id .value').should('contain', '1')
        })

        it ('Shows associated expense claims', () => {
            cy.get('.expense-claims-list').should('exist')
            cy.get('.expense-claims-list .items .item').should('have.length', 2)
            cy.get('.expense-claims-list .items .item .claim-id').each(item =>
                expect(item.text()).to.be.oneOf(['1', '2'])
            ) 
        })

        it ('Does not show view/edit mode button', () => {
            cy.get('.mode-selector').should('not.exist')
        })
    })

    describe('As manager', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })
    
            // specific session report manager
            cy.visit('app#/session-reports/2')
            cy.get('.session-manage-view-toggler').should('exist')
        })

        it ('Defaults to view mode', () => {
            cy.get('.session-report-view').should('exist')
            cy.get('.session-report-view .session-report .session-id .value').should('contain', '2')
        })

        it ('Shows associated expense claims', () => {
            cy.get('.expense-claims-list').should('exist')
            cy.get('.expense-claims-list .items .item').should('have.length', 1)
            cy.get('.expense-claims-list .items .item .claim-id').should('contain', '3')
        })

        it ('Does show edit button', () => {
            cy.get('.mode-selector').should('exist')
        })

        it ('Allows session report to be edited', () => {
            cy.get('.mode-selector .edit-report').click()
            cy.get('.session-report-edit-form').should('exist')
            cy.get('#meetingDetailsInput').clear().type("Something else")
            cy.get('.btn').contains("Save").click()

            // assert edit mode
            cy.get('.status').should('contain', "Report was saved successfully")
            cy.get('#meetingDetailsInput').should('have.value', "Something else")

            // assert view mode 
            cy.get('.mode-selector .view-report').click()
            cy.get('.session-report-view .session-report .meeting-details .value').should('contain', "Something else")
        })

        it ('Allows session report to be deleted', () => {
            cy.get('.mode-selector .edit-report').click()
            cy.get('.session-report-edit-form').should('exist')
            cy.get('.edit-actions .btn').contains("Delete").click()
            cy.get('.modal .btn').contains("Delete").click() // confirm dialog

            cy.get('.status').should('contain', "Report was deleted successfully")
            cy.get('.expense-claims-list .items .item').should('have.length', 0)
        })
    })

    describe('As admin', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.admin)
            })
    
            // specific session report manager
            cy.visit('app#/session-reports/2')
            cy.get('.session-manage-view-toggler').should('exist')
        })

        it ('Defaults to view mode', () => {
            cy.get('.session-report-view').should('exist')
            cy.get('.session-report-view .session-report .session-id .value').should('contain', '2')
        })

        it ('Shows associated expense claims', () => {
            cy.get('.expense-claims-list').should('exist')
            cy.get('.expense-claims-list .items .item').should('have.length', 1)
            cy.get('.expense-claims-list .items .item .claim-id').should('contain', '3')
        })

        it ('Does show edit button', () => {
            cy.get('.mode-selector').should('exist')
        })

        it ('Allows session report to be edited', () => {
            cy.get('.mode-selector .edit-report').click()
            cy.get('.session-report-edit-form').should('exist')
            cy.get('#meetingDetailsInput').clear().type("Something else")
            cy.get('.btn').contains("Save").click()

            // assert edit mode
            cy.get('.status').should('contain', "Report was saved successfully")
            cy.get('#meetingDetailsInput').should('have.value', "Something else")

            // assert view mode 
            cy.get('.mode-selector .view-report').click()
            cy.get('.session-report-view .session-report .meeting-details .value').should('contain', "Something else")
        })

        it ('Allows session report to be deleted', () => {
            cy.get('.mode-selector .edit-report').click()
            cy.get('.session-report-edit-form').should('exist')
            cy.get('.edit-actions .btn').contains("Delete").click()
            cy.get('.modal .btn').contains("Delete").click() // confirm dialog

            cy.get('.status').should('contain', "Report was deleted successfully")
            cy.get('.expense-claims-list .items .item').should('have.length', 0)
        })
    })
})