'use strict'

spawn = require('child_process').spawn

module.exports = (grunt) ->
    grunt.initConfig
        pkg: grunt.file.readJSON 'package.json'
        php:
            options:
                port: 8000
                base: 'public'
                router: 'dev.php'
                keepalive: false
            dev:
                options:
                    keepalive: true
            test:
                options: {}
        protractor:
            options:
                configFile: "public/config/protractor.conf.js"
                keepAlive: true # If false, the grunt process stops when the test fails.
                noColor: false # If true, protractor will not use colors in its output.
                specs: ["public/test/e2e/homepage.js"]
            firefox:
                options:
                    args: {browser: 'firefox'}
            chrome:
                options:
                    args: {browser: 'chrome'}
        watch:
            coffee:
                files: ['Gruntfile.coffee', 'public/coffee/**/*.coffee']
                tasks: ['coffee']
            unit_tests:
                files: ['public/coffee/test/unit/*.coffee']
                tasks: ['karma:unit']
            e2e_tests:
                files: ['public/coffee/test/e2e/*.coffee']
                tasks: ['protractor:chrome']
            css:
                files: ['public/compass/sass/*.scss']
                tasks: ['compass']
            protractor_configuration:
                files: ['public/config/protractor.conf.js']
                tasks: ['e2e']
            karma_configuration:
                files: ['public/config/karma.conf.js']
                tasks: ['karma:unit']
        coffee:
            compileWithMaps:
                options:
                    sourceMap: true
                files:
                    'public/js/git-walrus.js': ['public/coffee/walrus/*.coffee']
            compile:
                files:
                    'public/js/test/unit.js': ['public/coffee/test/unit/*.coffee']
                    'public/js/test/e2e.js': ['public/coffee/test/e2e/*.coffee']
        compass:
            dev:
                options:
                    basePath: 'public/compass'
                    sassDir: 'sass'
                    cssDir: 'stylesheets'
        karma:
            unit:
                configFile: 'public/config/karma.conf.js'
                singleRun: true
            travis:
                configFile: 'public/config/karma.conf.js'
                singleRun: true
                reporters: 'dots'
        concurrent:
            protractor: ['protractor:firefox', 'protractor:chrome']
        shell:
            phpserver:
                command: '/usr/bin/php -S localhost:8000 -t public public/dev.php'
                options:
                    async: true

    grunt.loadNpmTasks 'grunt-contrib-watch'
    grunt.loadNpmTasks 'grunt-contrib-coffee'
    grunt.loadNpmTasks 'grunt-contrib-compass'
    grunt.loadNpmTasks 'grunt-protractor-runner'
    grunt.loadNpmTasks 'grunt-karma'
    grunt.loadNpmTasks 'grunt-notify'
    grunt.loadNpmTasks 'grunt-php'
    grunt.loadNpmTasks 'grunt-concurrent'
    grunt.loadNpmTasks 'grunt-shell-spawn'

    grunt.registerTask 'default', ['test']
    grunt.registerTask 'e2e', ['coffee', 'concurrent:protractor']
    grunt.registerTask 'e2e-chrome', ['coffee', 'protractor:chrome']
    grunt.registerTask 'unit', ['coffee', 'karma:unit']
    grunt.registerTask 'test', ['unit', 'e2e']
    grunt.registerTask 'serve', ['watch', 'php:dev']
