describe('Search expenses', () => {
    beforeEach(() => {
        cy.server()
        cy.route('**/api/expense-claims*').as('searchExpenseClaims')
    })

    describe('For mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })

            // expenses
            cy.visit('/expenses')
            cy.url().should('contain', '/expenses/search')
            cy.wait('@searchExpenseClaims')
        })

        describe('Default search', () => {
            it('Displays claims for mentor only', () => {
                cy.get('.expense-claim-list .items .item').should('have.length', 2)
                cy.get('.expense-claim-list .items .item .claim-id').each(item =>
                    expect(item.text()).to.be.oneOf(['1', '2'])
                )
            })
        })

        describe('Search by created dates', () => {
            it('Displays claims where matched', () => {
                cy.get('.expense-claim-search .btn').contains('Today').click()
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 2)
            })

            it('Does not display claims where no match', () => {
                cy.get('.expense-claim-search .btn').contains('Yesterday').click()
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 0)
            })
        })

        describe('Search parameters', () => {
            it('Saves search parameters as query parameters', () => {
                cy.get('.expense-claim-search .btn').contains('Today').click()
                cy.get('.expense-claim-search .btn').contains('Search').click()

                const today = Cypress.moment().format('DD-MM-YYYY')
                cy.url().should('contain', `/expenses/search?created_date_range_start=${today}&created_date_range_end=${today}`)
            })
            it('Restores search parameters from query parameters', () => {
                cy.visit (`/app#/expenses/search?created_date_range_start=01-01-2019&created_date_range_end=25-07-2019`)

                cy.get('.expense-claim-search #createdDateRangeStartInput').should('have.value', '01-01-2019')
                cy.get('.expense-claim-search #createdDateRangeEndInput').should('have.value', '25-07-2019')
            })
        })
    })

    describe('For a manager', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })
           
            // expenses
            cy.visit('/expenses')
            cy.url().should('contain', '/expenses/search')
            cy.wait('@searchExpenseClaims')
        })

        describe('Default search', () => {
            it('Displays claims for managed mentors only', () => {
                cy.get('.expense-claim-list .items .item').should('have.length', 3)
                cy.get('.expense-claim-list .items .item .mentor-name').each(item =>
                    expect(item.text()).to.be.oneOf(['mentor-1', 'mentor-2'])
                ) 
            })
        })

        describe('Search by created dates', () => {
            it('Displays claims where matched', () => {
                cy.get('.expense-claim-search .btn').contains('Today').click()
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 3)
            })

            it('Does not display claims where no match', () => {
                cy.get('.expense-claim-search .btn').contains('Yesterday').click()
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 0)
            })
        })

        describe('Search by mentor', () => {
            it('Displays claims for mentor only', () => {
                cy.get('.expense-claim-search select[id=mentorSelect]').select('mentor-2')
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 1)
                cy.get('.expense-claim-list .items .item .mentor-name').should('contain', 'mentor-2')
            })
        })

        describe('Search by status', () => {
            it('Displays claims for status only', () => {
                cy.get('.expense-claim-search select[id=statusSelect]').select('Rejected')
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 1)
                cy.get('.expense-claim-list .items .item .status').should('contain', 'rejected')
            })
        })

        describe('Search parameters', () => {
            it('Saves search parameters as query parameters', () => {
                cy.get('.expense-claim-search .btn').contains('Today').click()
                cy.get('.expense-claim-search select[id=mentorSelect]').select('mentor-2')
                cy.get('.expense-claim-search select[id=statusSelect]').select('Rejected')
                cy.get('.expense-claim-search .btn').contains('Search').click()

                const today = Cypress.moment().format('DD-MM-YYYY')
                cy.url().should('contain', `/expenses/search?mentor_id=6&status=Rejected&created_date_range_start=${today}&created_date_range_end=${today}`)

            })
            it('Restores search parameters from query parameters', () => {
                cy.visit (`/app#/expenses/search?mentor_id=6&status=Rejected&created_date_range_start=01-01-2019&created_date_range_end=25-07-2019`)

                cy.get('.expense-claim-search #mentorSelect').should('have.value', '6')
                cy.get('.expense-claim-search #createdDateRangeStartInput').should('have.value', '01-01-2019')
                cy.get('.expense-claim-search #createdDateRangeEndInput').should('have.value', '25-07-2019')
            })
        })
    })

    describe('For an admin', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.admin)
            })
        
            // expenses
            cy.visit('/expenses')
            cy.url().should('contain', '/expenses/search')
            cy.wait('@searchExpenseClaims')
        })

        describe('Default search', () => {
            it('Displays claims for all mentors', () => {
                cy.get('.expense-claim-list .items .item').should('have.length', 4)
                cy.get('.expense-claim-list .items .item .mentor-name').each(item =>
                    expect(item.text()).to.be.oneOf(['mentor-1', 'mentor-2', 'mentor-3'])
                ) 
            })
        })

        describe('Search by created dates', () => {
            it('Displays claims where matched', () => {
                cy.get('.expense-claim-search .btn').contains('Today').click()
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 4)
            })

            it('Does not display claims where no match', () => {
                cy.get('.expense-claim-search .btn').contains('Yesterday').click()
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 0)
            })
        })

        describe('Search by mentor', () => {
            it('Displays claims for mentor only', () => {
                cy.get('.expense-claim-search select[id=mentorSelect]').select('mentor-2')
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 1)
                cy.get('.expense-claim-list .items .item .mentor-name').should('contain', 'mentor-2')
            })
        })

        describe('Search by status', () => {
            it('Displays claims for status only', () => {
                cy.get('.expense-claim-search select[id=statusSelect]').select('Rejected')
                cy.get('.expense-claim-search .btn').contains('Search').click()
                cy.wait('@searchExpenseClaims')

                cy.get('.expense-claim-list .items .item').should('have.length', 1)
                cy.get('.expense-claim-list .items .item .status').should('contain', 'rejected')
            })
        })

        describe('Search parameters', () => {
            it('Saves search parameters as query parameters', () => {
                cy.get('.expense-claim-search .btn').contains('Today').click()
                cy.get('.expense-claim-search select[id=mentorSelect]').select('mentor-2')
                cy.get('.expense-claim-search select[id=statusSelect]').select('Rejected')
                cy.get('.expense-claim-search .btn').contains('Search').click()

                const today = Cypress.moment().format('DD-MM-YYYY')
                cy.url().should('contain', `/expenses/search?mentor_id=6&status=Rejected&created_date_range_start=${today}&created_date_range_end=${today}`)

            })
            it('Restores search parameters from query parameters', () => {
                cy.visit (`/app#/expenses/search?mentor_id=6&status=Rejected&created_date_range_start=01-01-2019&created_date_range_end=25-07-2019`)

                cy.get('.expense-claim-search #mentorSelect').should('have.value', '6')
                cy.get('.expense-claim-search #createdDateRangeStartInput').should('have.value', '01-01-2019')
                cy.get('.expense-claim-search #createdDateRangeEndInput').should('have.value', '25-07-2019')
            })
        })
    })
})

describe('Expense claim workflow', () => {
    beforeEach(() => {
        cy.server()
        cy.route('**/api/expense-claims/*').as('findExpenseClaim')
        cy.route('**/api/expense-claims*').as('searchExpenseClaims')
    })

    describe('For mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })

            // expenses search
            cy.visit('/expenses')
            cy.url().should('contain', '/expenses/search')
            cy.wait("@searchExpenseClaims")
            cy.get('.expense-claim-list .items .item').first().click()
            cy.wait("@findExpenseClaim")
            cy.get('.expense-claim-workflow').should('exist')

        })

        it('Shows workflow nav bar', () => {
            cy.get('.workflow-nav').should('exist')
        })

        it('Shows selected claim in view mode', () => {
            cy.get('.expense-claim-manage').should('exist')
            cy.get('.expense-claim-view').should('exist')
            cy.get('.expense-claim-view .expense-claim .claim-id .value').should('contain', '1')
        })

        it('Allows workflow to be closed', () => {
            cy.get('.close-workflow .btn').click()
            cy.url().should('contain', '/expenses/search')
        })
    })

    describe('For manager', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })

            // expenses search
            cy.visit('/expenses')
            cy.url().should('contain', '/expenses/search')
            cy.wait("@searchExpenseClaims")
            cy.get('.expense-claim-list .items .item').first().click()
            cy.wait("@findExpenseClaim")
            cy.get('.expense-claim-workflow').should('exist')
        })

        it('Shows workflow nav bar', () => {
            cy.get('.workflow-nav').should('exist')
        })

        it('Allows navigation', () => {
            cy.get('.navigation-buttons .next-claim').click()

            cy.get('.expense-claim-view').should('exist')
            cy.get('.expense-claim-view .expense-claim .claim-id .value').should('contain', '2')
        })

        it('Shows selected claim in view-mode', () => {
            cy.get('.expense-claim-manage').should('exist')
            cy.get('.expense-claim-view').should('exist')
            cy.get('.expense-claim-view .expense-claim .claim-id .value').should('contain', '1')
        })

        it('Allows workflow to be closed', () => {
            cy.get('.close-workflow .btn').click()
            cy.url().should('contain', '/expenses/search')
        })
    })

    describe('For admin', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.admin)
            })

            // expense claims search
            cy.visit('/expenses')
            cy.url().should('contain', '/expenses/search')
            cy.wait("@searchExpenseClaims")
            cy.get('.expense-claim-list .items .item').first().click()
            cy.wait("@findExpenseClaim")
            cy.get('.expense-claim-workflow').should('exist')
        })

        it('Shows workflow nav bar', () => {
            cy.get('.workflow-nav').should('exist')
        })

        it('Allows navigation', () => {
            cy.get('.navigation-buttons .next-claim').click()

            cy.get('.expense-claim-view').should('exist')
            cy.get('.expense-claim-view .expense-claim .claim-id .value').should('contain', '3')
        })

        it('Shows selected expense claim in view-mode', () => {
            cy.get('.expense-claim-manage').should('exist')
            cy.get('.expense-claim-view').should('exist')
            cy.get('.expense-claim-view .expense-claim .claim-id .value').should('contain', '4')
        })

        it('Allows workflow to be closed', () => {
            cy.get('.close-workflow .btn').click()
            cy.url().should('contain', '/expenses/search')
        })
    })
})

describe('Expense claim manage', () => {
    beforeEach(() => {
        cy.server()
        cy.route('**/api/expense-claims/*').as('findExpenseClaim')
        cy.route('**/api/expense-claims*').as('searchExpenseClaims')
        cy.route('**/api/session-reports/*').as('findSessionReport')
    })

    describe('As mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.mentor)
            })
    
            // specific expense claim manager
            cy.visit('app#/expenses/1')
            cy.wait('@findExpenseClaim')
            cy.get('.expense-claim-manage').should('exist')
        })

        it ('Displays in view mode', () => {
            cy.get('.expense-claim-view').should('exist')
            cy.get('.expense-claim-view .expense-claim .claim-id .value').should('contain', '1')
        })

        it('Shows associated session report', () => {
            cy.wait('@findSessionReport')

            cy.get('.session-report-list').should('exist')
            cy.get('.session-report-list .items .item').should('have.length', 1)
            cy.get('.session-report-list .items .item .session-id').should('contain', '1')
        })

        it ('Does not allow processing of expense claim', () => {
            cy.get('.process-expense-claim').should('not.exist')
        })
       
        it ('Does not allow rejecting of expense claim', () => {
            cy.get('.reject-expense-claim').should('not.exist')
        })
    })

    describe('As manager', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.manager)
            })
    
            // specific expense claim manager
            cy.visit('app#/expenses/2')
            cy.wait('@findExpenseClaim')
            cy.get('.expense-claim-manage').should('exist')
        })

        it ('Defaults to view mode', () => {
            cy.get('.expense-claim-view').should('exist')
            cy.get('.expense-claim-view .expense-claim .claim-id .value').should('contain', '2')
        })

        it('Shows associated session report', () => {
            cy.wait('@findSessionReport')

            cy.get('.session-report-list').should('exist')
            cy.get('.session-report-list .items .item').should('have.length', 1)
            cy.get('.session-report-list .items .item .session-id').should('contain', '1')
        })

        it ('Does not allow processing of expense claim', () => {
            cy.get('.process-expense-claim').should('not.exist')
        })
       
        it ('Does not allow rejecting of expense claim', () => {
            cy.get('.reject-expense-claim').should('not.exist')
        })
    })

    describe('As admin', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
                cy.login(users.admin)
            })
    
            // specific expense claim manager
            cy.visit('app#/expenses/2')
            cy.wait('@findExpenseClaim')
            cy.get('.expense-claim-manage').should('exist')
        })

        it ('Defaults to view mode', () => {
            cy.get('.expense-claim-view').should('exist')
            cy.get('.expense-claim-view .expense-claim .claim-id .value').should('contain', '2')
        })

        it('Shows associated session report', () => {
            cy.wait('@findSessionReport')

            cy.get('.session-report-list').should('exist')
            cy.get('.session-report-list .items .item').should('have.length', 1)
            cy.get('.session-report-list .items .item .session-id').should('contain', '1')
        })

        it('Does allow processing of expense claim', () => {
            cy.get('.btn.process-expense-claim').click()

            cy.get('.expense-claim .status .value').should('contain', 'processed')
            cy.get('.btn.process-expense-claim').should('not.exist')
            cy.get('.btn.reverse-processing').should('exist')
        })
       
        it('Does allow rejecting of expense claim', () => {
            cy.get('.btn.reject-expense-claim').click()

            cy.get('.expense-claim .status .value').should('contain', 'rejected')
            cy.get('.btn.reject-expense-claim').should('not.exist')
            cy.get('.btn.reverse-processing').should('exist')
        })

        it('Does allow reverse processing of expense claim', () => {
            cy.get('.reject-expense-claim').click()
            cy.get('.btn.reverse-processing').click()
            cy.get('.btn.reverse-processing-confirm').click()

            cy.get('.expense-claim .status .value').should('contain', 'pending')
            cy.get('.btn.process-expense-claim').should('exist')
            cy.get('.btn.reject-expense-claim').should('exist')
            cy.get('.btn.reverse-processing').should('not.exist')
        })
    })
})