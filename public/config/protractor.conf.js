/**
 * User: matteo
 * Date: 03/12/13
 * Time: 22.41
 * @matteosister
 * Just for fun...
 */

exports.config = {
    // The address of a running selenium server.
    seleniumAddress: 'http://localhost:4444/wd/hub',

    // Capabilities to be passed to the webdriver instance.
    capabilities: {
        'browserName': 'chrome'
        //'browserName': 'phantomjs'

    },

    // Spec patterns are relative to the current working directly when
    // protractor is called.
    specs: ['../js/test/e2e.js'],

    // Options to be passed to Jasmine-node.
    jasmineNodeOpts: {
        showColors: true,
        defaultTimeoutInterval: 30000
    },

    onPrepare: function() {
        global.findBy = protractor.By;
    }
};