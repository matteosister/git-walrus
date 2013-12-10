/**
 * User: matteo
 * Date: 21/11/13
 * Time: 22.56
 * @matteosister
 * Just for fun...
 */

module.exports = function (config) {
    config.set({
        basePath: '../',

        files: [
            'bower/angular/angular.js',
            'bower/angular-*/angular-*.js',
            'js/**/*.js',
            'test/unit/**/*.js'
        ],

        exclude: [
            'bower/angular/angular-loader.js',
            'bower/angular/*.min.js',
            'bower/angular-scenario/angular-scenario.js'
        ],

        autoWatch: true,

        frameworks: ['jasmine'],

        //browsers: ['Chrome'],
        browsers: ['PhantomJS'],
        //browsers: ['PhantomJS', 'Chrome'],

        plugins: [
            'karma-junit-reporter',
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-phantomjs-launcher',
            'karma-script-launcher',
            'karma-ubuntu-reporter',
            'karma-jasmine'
        ],

        reporters: ['ubuntu', 'progress'],

        junitReporter: {
            outputFile: 'test_out/unit.xml',
            suite: 'unit'
        },

        logLevel: config.LOG_INFO
    });
};