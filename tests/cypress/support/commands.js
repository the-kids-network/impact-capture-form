// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add("login", (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add("drag", { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add("dismiss", { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite("visit", (originalFn, url, options) => { ... })


Cypress.Commands.add('login', (user) => {
    Cypress.log({
      name: 'login',
      message: `${user.email} | ${user.password}`,
    })

    cy.visit("/login")

    cy.window()
      .then(window => {
        const csrf = window.Spark.csrfToken

        cy.request(
            {
                method: 'POST',
                url: '/login',
                form: true,
                body: {
                _token: csrf,
                email: user.email,
                password: user.password
            }
        })
    })    
  })