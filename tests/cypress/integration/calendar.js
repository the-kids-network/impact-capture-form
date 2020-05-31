describe('Calendar View', () => {
   
    describe('For mentor', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
               cy.login(users.mentor)
           })

           // Switch to current day in calendar view
           cy.visit('/calendar')
           cy.get('.fc-toolbar .fc-dayGridDay-button').click()
        })

        it('Shows planned sessions for mentor only', () => {
            cy.get('.planned-session-event').should('have.length', 1)
            // displays mentee name
            cy.get('.planned-session-event').first().should('contain', 'mentee 1')
        })
    
        it('Shows mentor leave for mentor only', () => {
            cy.get('.mentor-leave-event').should('have.length', 1)
            // displays 'Leave'
            cy.get('.mentor-leave-event').first().should('contain', 'Leave')
        })
    
        it('Shows mentee leave for mentored mentee', () => {
            cy.get('.mentee-leave-event').should('have.length', 1)
            // displays mentee name in leave
            cy.get('.mentee-leave-event').first().should('contain', 'mentee 1 - Leave')
        })
    })

    describe('For manager', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
               cy.login(users.manager)
           })

           // Switch to current day in calendar view
           cy.visit('/calendar')
           cy.get('.fc-toolbar .fc-dayGridDay-button').click()
        })

        it('Shows planned sessions for managed mentors only', () => {
            cy.get('.planned-session-event').should('have.length', 2)
            // displays mentor and mentee name
            cy.get('.planned-session-event').eq(0).should('contain', 'mentor-1 - mentee 1')
            cy.get('.planned-session-event').eq(1).should('contain', 'mentor-2 - mentee 2')

        })
    
        it('Shows mentor leave for managed mentors only', () => {
            cy.get('.mentor-leave-event').should('have.length', 2)
            // displays mentor name and Leave
            cy.get('.mentor-leave-event').eq(0).should('contain', 'mentor-1 - Leave')
            cy.get('.mentor-leave-event').eq(1).should('contain', 'mentor-2 - Leave')
        })
    
        it('Shows mentee leave for manged mentor\'s mentees', () => {
            cy.get('.mentee-leave-event').should('have.length', 2)
            // displays mentee name in leave
            cy.get('.mentee-leave-event').eq(0).should('contain', 'mentee 1 - Leave')
            cy.get('.mentee-leave-event').eq(1).should('contain', 'mentee 2 - Leave')
        })
    })

    describe('For admin', () => {
        beforeEach(() => {
            // Login
            cy.fixture('users').then((users) => {
               cy.login(users.admin)
           })

           // Switch to current day in calendar view
           cy.visit('/calendar')
           cy.get('.fc-toolbar .fc-dayGridDay-button').click()
        })

        it('Shows planned sessions for all mentors', () => {
            cy.get('.planned-session-event').should('have.length', 4)
            // displays mentor and mentee name
            cy.get('.planned-session-event').eq(0).should('contain', 'mentor-1 - mentee 1')
            cy.get('.planned-session-event').eq(2).should('contain', 'mentor-3 - mentee 3')
        })
    
        it('Shows mentor leave for all mentors', () => {
            cy.get('.mentor-leave-event').should('have.length', 3)
            // displays mentor name and Leave
            cy.get('.mentor-leave-event').eq(0).should('contain', 'mentor-1 - Leave')
            cy.get('.mentor-leave-event').eq(2).should('contain', 'mentor-3 - Leave')
        })
    
        it('Shows mentee leave for all mentees', () => {
            cy.get('.mentee-leave-event').should('have.length', 3)
            // displays mentee name in leave
            cy.get('.mentee-leave-event').eq(0).should('contain', 'mentee 1 - Leave')
            cy.get('.mentee-leave-event').eq(2).should('contain', 'mentee 3 - Leave')
        })
    })
})

describe('Planned Session', () => {
   
    describe('Creation', () => {
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.mentor)
               })
    
               cy.visit('/planned-session/new')
            })

            it('Should only display mentor\'s mentees', () => {
                cy.get('#menteeInput option').should('have.length', 1)
                cy.get('#menteeInput option').should('contain', 'mentee 1')
            })

            it('Successfully creates planned session', () => {
                cy.get('#sessionDateInput').type('28-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#sessionLocation').type('Park')
                cy.get('.btn[value=Create]').click()

                cy.get('.alert-success').should('contain', 'Planned Session Created')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#sessionDateInput').should('have.value', '28-05-2020')
                cy.get('#sessionLocation').should('have.value', 'Park')
            })
        })  

        describe('For manager', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.manager)
               })
    
               cy.visit('/planned-session/new')
            })

            it('Should only display managed mentor\'s mentees', () => {
                cy.get('#menteeInput option').should('have.length', 2)
                cy.get('#menteeInput option').eq(0).should('contain', 'mentee 1')
                cy.get('#menteeInput option').eq(1).should('contain', 'mentee 2')
            })

            it('Successfully creates planned session', () => {
                cy.get('#sessionDateInput').type('28-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#sessionLocation').type('Park')
                cy.get('.btn[value=Create]').click()

                cy.get('.alert-success').should('contain', 'Planned Session Created')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#sessionDateInput').should('have.value', '28-05-2020')
                cy.get('#sessionLocation').should('have.value', 'Park')
            })
        }) 

        describe('For admin', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.admin)
               })
    
               cy.visit('/planned-session/new')
            })

            it('Should display all mentees', () => {
                cy.get('#menteeInput option').should('have.length', 4)
                cy.get('#menteeInput option').each(item =>
                    expect(item.text()).to.be.oneOf(['mentee 1', 'mentee 2', 'mentee 3', 'mentee 4'])
                )
            })

            it('Successfully creates planned session', () => {
                cy.get('#sessionDateInput').type('28-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#sessionLocation').type('Park')
                cy.get('.btn[value=Create]').click()

                cy.get('.alert-success').should('contain', 'Planned Session Created')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#sessionDateInput').should('have.value', '28-05-2020')
                cy.get('#sessionLocation').should('have.value', 'Park')
            })
        }) 
    })

    describe('Edit', () => {
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.mentor)
               })
            })

            it('Successfully updates planned session', () => {
                cy.visit('/planned-session/1')
                cy.get('#sessionDateInput').clear().type('29-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#sessionLocation').clear().type('Somewhere else')
                cy.get('.btn[value=Save]').click()

                cy.get('.alert-success').should('contain', 'Planned Session Updated')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#sessionDateInput').should('have.value', '29-05-2020')
                cy.get('#sessionLocation').should('have.value', 'Somewhere else')
            })

            it('Fails with unauthorised for planned session not belonging to mentor', () => {
                cy.visit('/planned-session/2', {failOnStatusCode: false})
                cy.get('body .message').should('contain', 'Unauthorized')
            })
        })  

        describe('For manager', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.manager)
               })
            })

            it('Successfully updates planned session', () => {
                cy.visit('/planned-session/1')
                cy.get('#sessionDateInput').clear().type('29-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#sessionLocation').clear().type('Somewhere else')
                cy.get('.btn[value=Save]').click()

                cy.get('.alert-success').should('contain', 'Planned Session Updated')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#sessionDateInput').should('have.value', '29-05-2020')
                cy.get('#sessionLocation').should('have.value', 'Somewhere else')
            })

            it('Fails with unauthorised for planned session not belonging to one of managed mentors', () => {
                cy.visit('/planned-session/2')

                cy.visit('/planned-session/3', {failOnStatusCode: false})
                cy.get('body .message').should('contain', 'Unauthorized')
            })
        })  

        describe('For admin', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.admin)
               })
            })

            it('Successfully updates planned session', () => {
                cy.visit('/planned-session/1')
                cy.get('#sessionDateInput').clear().type('29-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#sessionLocation').clear().type('Somewhere else')
                cy.get('.btn[value=Save]').click()

                cy.get('.alert-success').should('contain', 'Planned Session Updated')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#sessionDateInput').should('have.value', '29-05-2020')
                cy.get('#sessionLocation').should('have.value', 'Somewhere else')
            })

            it('Can view any planned session for any mentor', () => {
                cy.visit('/planned-session/2')
                cy.visit('/planned-session/3')
            })
        })  
    })

    describe('Delete', ()=> {
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.mentor)
               })
    
               cy.visit('/planned-session/1')
            })

            it('Successfully deletes planned session', () => {
                cy.get('.btn[value=Delete]').click()

                cy.url().should('contain', '/calendar')
                cy.get('.fc-toolbar .fc-dayGridDay-button').click()
                cy.get('.planned-session-event').should('have.length', 0)
            })
        })
        describe('For manager', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.manager)
               })
    
               cy.visit('/planned-session/1')
            })

            it('Successfully deletes planned session', () => {
                cy.get('.btn[value=Delete]').click()

                cy.url().should('contain', '/calendar')
                cy.get('.fc-toolbar .fc-dayGridDay-button').click()
                cy.get('.planned-session-event').should('have.length', 1)
            })
        })
        describe('For admin', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.admin)
               })
    
               cy.visit('/planned-session/1')
            })

            it('Successfully deletes planned session', () => {
                cy.get('.btn[value=Delete]').click()

                cy.url().should('contain', '/calendar')
                cy.get('.fc-toolbar .fc-dayGridDay-button').click()
                cy.get('.planned-session-event').should('have.length', 3)
            })
        })
    })
})

describe('Mentor Leave', () => {
   
    describe('Creation', () => {
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.mentor)
               })
    
               cy.visit('/mentor/leave/new')
            })

            it('Successfully creates leave', () => {
                cy.get('#startDateInput').type('28-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').type('29-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').type('Park')
                cy.get('.btn[value=Create]').click()

                cy.get('.alert-success').should('contain', 'Leave Created')
                cy.get('#mentorInput').should('not.exist')
                cy.get('#startDateInput').should('have.value', '28-05-2020')
                cy.get('#endDateInput').should('have.value', '29-05-2020')
                cy.get('#descriptionInput').should('have.value', 'Park')
            })
        }) 

        describe('For manager', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.manager)
               })
    
               cy.visit('/mentor/leave/new')
            })

            it('Show managed mentors', () => {
                cy.get('#mentorInput option').should('have.length', 2)
                cy.get('#mentorInput option').each(item =>
                    expect(item.text()).to.be.oneOf(['mentor-1', 'mentor-2'])
                )
            })

            it('Successfully creates leave', () => {
                cy.get('#startDateInput').type('28-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').type('29-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').type('Park')
                cy.get('.btn[value=Create]').click()

                cy.get('.alert-success').should('contain', 'Leave Created')
                cy.get('#mentorInput').should('have.value', 'mentor-1')
                cy.get('#startDateInput').should('have.value', '28-05-2020')
                cy.get('#endDateInput').should('have.value', '29-05-2020')
                cy.get('#descriptionInput').should('have.value', 'Park')
            })
        })  

        describe('For admin', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.admin)
               })
    
               cy.visit('/mentor/leave/new')
            })

            it('Shows all mentors', () => {
                cy.get('#mentorInput option').should('have.length', 4)
                cy.get('#mentorInput option').each(item =>
                    expect(item.text()).to.be.oneOf(['mentor-1', 'mentor-2', 'mentor-3', 'mentor-4'])
                )
            })

            it('Successfully creates leave', () => {
                cy.get('#startDateInput').type('28-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').type('29-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').type('Park')
                cy.get('.btn[value=Create]').click()

                cy.get('.alert-success').should('contain', 'Leave Created')
                cy.get('#mentorInput').should('have.value', 'mentor-1')
                cy.get('#startDateInput').should('have.value', '28-05-2020')
                cy.get('#endDateInput').should('have.value', '29-05-2020')
                cy.get('#descriptionInput').should('have.value', 'Park')
            })
        })  
    })

    describe('Edit', () => {
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.mentor)
               })
    
               cy.visit('/mentor/leave/1')
            })

            it('Successfully updates leave', () => {
                cy.get('#startDateInput').clear().type('28-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').clear().type('29-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').clear().type('Somewhere else')
                cy.get('.btn[value=Save]').click()

                cy.get('.alert-success').should('contain', 'Leave Updated')
                cy.get('#mentorInput').should('not.exist')
                cy.get('#startDateInput').should('have.value', '28-04-2020')
                cy.get('#endDateInput').should('have.value', '29-04-2020')
                cy.get('#descriptionInput').should('have.value', 'Somewhere else')
            })
        }) 
        describe('For manager', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.manager)
               })
    
               cy.visit('/mentor/leave/1')
            })

            it('Successfully updates leave', () => {
                cy.get('#startDateInput').clear().type('28-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').clear().type('29-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').clear().type('Somewhere else')
                cy.get('.btn[value=Save]').click()

                cy.get('.alert-success').should('contain', 'Leave Updated')
                cy.get('#mentorInput').should('have.value', 'mentor-1')
                cy.get('#startDateInput').should('have.value', '28-04-2020')
                cy.get('#endDateInput').should('have.value', '29-04-2020')
                cy.get('#descriptionInput').should('have.value', 'Somewhere else')
            })
        })   
        describe('For admin', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.admin)
               })
    
               cy.visit('/mentor/leave/1')
            })

            it('Successfully updates leave', () => {
                cy.get('#startDateInput').clear().type('28-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').clear().type('29-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').clear().type('Somewhere else')
                cy.get('.btn[value=Save]').click()

                cy.get('.alert-success').should('contain', 'Leave Updated')
                cy.get('#mentorInput').should('have.value', 'mentor-1')
                cy.get('#startDateInput').should('have.value', '28-04-2020')
                cy.get('#endDateInput').should('have.value', '29-04-2020')
                cy.get('#descriptionInput').should('have.value', 'Somewhere else')
            })
        }) 
    })

    describe('Delete', ()=> {
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.mentor)
               })
    
               cy.visit('/mentor/leave/1')
            })

            it('Successfully deletes leaves', () => {
                cy.get('.btn[value=Delete]').click()

                cy.url().should('contain', '/calendar')
                cy.get('.fc-toolbar .fc-dayGridDay-button').click()
                cy.get('.mentor-leave-event').should('have.length', 0)
            })
        })
        describe('For manager', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.manager)
               })
    
               cy.visit('/mentor/leave/1')
            })

            it('Successfully deletes leaves', () => {
                cy.get('.btn[value=Delete]').click()

                cy.url().should('contain', '/calendar')
                cy.get('.fc-toolbar .fc-dayGridDay-button').click()
                cy.get('.mentor-leave-event').should('have.length', 1)
            })
        })
        describe('For admin', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.admin)
               })
    
               cy.visit('/mentor/leave/1')
            })

            it('Successfully deletes leaves', () => {
                cy.get('.btn[value=Delete]').click()

                cy.url().should('contain', '/calendar')
                cy.get('.fc-toolbar .fc-dayGridDay-button').click()
                cy.get('.mentor-leave-event').should('have.length', 2)
            })
        })
    })
})

describe('Mentee Leave', () => {
   
    describe('Creation', () => {
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.mentor)
               })
    
               cy.visit('/mentee/leave/new')
            })

            it('Shows assigned mentees only', ()=> {
                cy.get('#menteeInput option').should('have.length', 1)
                cy.get('#menteeInput option').should('contain', 'mentee 1')
            })

            it('Successfully creates leave', () => {
                cy.get('#startDateInput').type('28-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').type('29-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').type('Spain')
                cy.get('.btn[value=Create]').click()

                cy.get('.alert-success').should('contain', 'Leave Created')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#startDateInput').should('have.value', '28-05-2020')
                cy.get('#endDateInput').should('have.value', '29-05-2020')
                cy.get('#descriptionInput').should('have.value', 'Spain')
            })
        }) 
        describe('For manager', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.manager)
               })
    
               cy.visit('/mentee/leave/new')
            })

            it('Shows managed mentor\'s mentees only', ()=> {
                cy.get('#menteeInput option').should('have.length', 2)
                cy.get('#menteeInput option').each(item =>
                    expect(item.text()).to.be.oneOf(['mentee 1', 'mentee 2'])
                )
            })

            it('Successfully creates leave', () => {
                cy.get('#startDateInput').type('28-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').type('29-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').type('Spain')
                cy.get('.btn[value=Create]').click()

                cy.get('.alert-success').should('contain', 'Leave Created')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#startDateInput').should('have.value', '28-05-2020')
                cy.get('#endDateInput').should('have.value', '29-05-2020')
                cy.get('#descriptionInput').should('have.value', 'Spain')
            })
        }) 
        describe('For admin', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.admin)
               })
    
               cy.visit('/mentee/leave/new')
            })

            it('Shows all mentees', ()=> {
                cy.get('#menteeInput option').should('have.length', 4)
                cy.get('#menteeInput option').each(item =>
                    expect(item.text()).to.be.oneOf(['mentee 1', 'mentee 2', 'mentee 3', 'mentee 4'])
                )
            })

            it('Successfully creates leave', () => {
                cy.get('#startDateInput').type('28-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').type('29-05-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').type('Spain')
                cy.get('.btn[value=Create]').click()

                cy.get('.alert-success').should('contain', 'Leave Created')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#startDateInput').should('have.value', '28-05-2020')
                cy.get('#endDateInput').should('have.value', '29-05-2020')
                cy.get('#descriptionInput').should('have.value', 'Spain')
            })
        })  
    })

    describe('Edit', () => {
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.mentor)
               })
    
               cy.visit('/mentee/leave/1')
            })

            it('Successfully updates leave', () => {
                cy.get('#startDateInput').clear().type('28-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').clear().type('29-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').clear().type('Somewhere else')
                cy.get('.btn[value=Save]').click()

                cy.get('.alert-success').should('contain', 'Leave Updated')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#startDateInput').should('have.value', '28-04-2020')
                cy.get('#endDateInput').should('have.value', '29-04-2020')
                cy.get('#descriptionInput').should('have.value', 'Somewhere else')
            })
        })  
        describe('For manager', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.manager)
               })
    
               cy.visit('/mentee/leave/1')
            })

            it('Successfully updates leave', () => {
                cy.get('#startDateInput').clear().type('28-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').clear().type('29-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').clear().type('Somewhere else')
                cy.get('.btn[value=Save]').click()

                cy.get('.alert-success').should('contain', 'Leave Updated')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#startDateInput').should('have.value', '28-04-2020')
                cy.get('#endDateInput').should('have.value', '29-04-2020')
                cy.get('#descriptionInput').should('have.value', 'Somewhere else')
            })
        })  
        describe('For admin', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.admin)
               })
    
               cy.visit('/mentee/leave/1')
            })

            it('Successfully updates leave', () => {
                cy.get('#startDateInput').clear().type('28-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#endDateInput').clear().type('29-04-2020')
                cy.get('.card-body').click({force: true})
                cy.get('#descriptionInput').clear().type('Somewhere else')
                cy.get('.btn[value=Save]').click()

                cy.get('.alert-success').should('contain', 'Leave Updated')
                cy.get('#menteeInput').should('have.value', 'mentee 1')
                cy.get('#startDateInput').should('have.value', '28-04-2020')
                cy.get('#endDateInput').should('have.value', '29-04-2020')
                cy.get('#descriptionInput').should('have.value', 'Somewhere else')
            })
        })  
    })

    describe('Delete', ()=> {
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.mentor)
               })
    
               cy.visit('/mentee/leave/1')
            })

            it('Successfully deletes leaves', () => {
                cy.get('.btn[value=Delete]').click()

                cy.url().should('contain', '/calendar')
                cy.get('.fc-toolbar .fc-dayGridDay-button').click()
                cy.get('.mentee-leave-event').should('have.length', 0)
            })
        })
        describe('For manager', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.manager)
               })
    
               cy.visit('/mentee/leave/1')
            })

            it('Successfully deletes leaves', () => {
                cy.get('.btn[value=Delete]').click()

                cy.url().should('contain', '/calendar')
                cy.get('.fc-toolbar .fc-dayGridDay-button').click()
                cy.get('.mentee-leave-event').should('have.length', 1)
            })
        })
        describe('For mentor', () => {
            beforeEach(() => {
                // Login
                cy.fixture('users').then((users) => {
                   cy.login(users.admin)
               })
    
               cy.visit('/mentee/leave/1')
            })

            it('Successfully deletes leaves', () => {
                cy.get('.btn[value=Delete]').click()

                cy.url().should('contain', '/calendar')
                cy.get('.fc-toolbar .fc-dayGridDay-button').click()
                cy.get('.mentee-leave-event').should('have.length', 2)
            })
        })
    })
})