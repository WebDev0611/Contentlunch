module.exports = {
    'Login Test': function (browser) {
        browser
            .url(browser.launchUrl)
            .waitForElementVisible('body', 1000)
            .setValue('#email', 'admin@test.com')
            .setValue('#password', 'launch123')
            .waitForElementVisible('button[type=submit]', 1000)
            .click('button[type=submit]')
            .waitForElementVisible('body', 1000);

        browser.expect.element('.navigation').to.be.present;
        browser.end();
    }
};