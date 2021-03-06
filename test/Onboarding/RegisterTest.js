module.exports = {
    /**
     *
     * @param browser
     * @constructor
     */
    'Register Account': function (browser) {
        browser
            // Check login page and click register
            .url(browser.launchUrl)
            .waitForElementVisible('form[action$=login]', 1000, "Login form is visible")
            .click('a[href$=signup]')

            // Check signup page and fill out form
            .assert.urlContains("/signup", "Signup link works")
            .setValue('#name', 'Testy McTesterson')
            .setValue('#email', 'testy'+Math.random()+'@test.com')
            .setValue('#password', 'launch123')
            .setValue('input[name=password_confirmation]', 'launch123')
            .setValue('#company_name', 'ContentLaunch')
            .click('input[type=submit]')

            // Check invite page
            .assert.urlContains("/invite", "Account created successfully")
            .waitForElementVisible(".onboarding-step-point.active:nth-of-type(2)", 1000, "Progress meter on step 2")
            // TODO add a test for validating that invitation emails are sent out
            .click('a[href$=connect]')

            // Check connect page
            .assert.urlContains("/connect", "Navigated to Connect page successfully")
            .waitForElementVisible(".onboarding-step-point.active:nth-of-type(3)", 1000, "Progress meter on step 3")
            .click('a[href$=score]')

            // Check score page
            .assert.urlContains("/score", "Navigated to Score page successfully")
            .waitForElementVisible(".onboarding-step-point.active:nth-of-type(4)", 1000, "Progress meter shows complete")
            .url(browser.launchUrl)

            // Check Dashboard
            .waitForElementVisible('.navigation', 1000, 'Navigation panel is visible.')
            .waitForElementVisible('.navigation .icon-navigation-chart', 1000, 'Dashboard icon is visible.')
            .waitForElementVisible('.navigation .icon-navigation-content', 1000, 'Plan icon is visible.')
            .waitForElementVisible('.navigation .icon-navigation-dashboard', 1000, 'Create is visible.')
            .waitForElementVisible('.navigation .icon-navigation-calendar', 1000, 'Calendar is visible.');

        browser.end();
    }
};
