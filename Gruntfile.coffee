'use strict'

spawn = require('child_process').spawn

module.exports = (grunt) ->
    grunt.initConfig
        pkg: grunt.file.readJSON 'package.json'
        php:
            options:
                port: 8000
                keepalive: false
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
                tasks: ['coffee', 'karma:unit']
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

    grunt.loadNpmTasks 'grunt-contrib-watch'
    grunt.loadNpmTasks 'grunt-contrib-coffee'
    grunt.loadNpmTasks 'grunt-contrib-compass'
    grunt.loadNpmTasks 'grunt-protractor-runner'
    grunt.loadNpmTasks 'grunt-karma'
    grunt.loadNpmTasks 'grunt-notify'
    grunt.loadNpmTasks 'grunt-php'
    grunt.loadNpmTasks 'grunt-concurrent'

    grunt.registerTask 'default', ['php', 'watch']
    grunt.registerTask 'e2e', ['php', 'coffee', 'concurrent:protractor']
    grunt.registerTask 'unit', ['coffee', 'karma:unit']
    grunt.registerTask 'test', ['unit', 'e2e']

    ###grunt.registerTask 'php-server', 'start a php server instance', () ->
        done = @async()
        server = spawn('/usr/bin/php', ['-S', 'localhost:8000', '-t', 'public/', 'public/dev.php'])
        server.stdout.on 'data', (data) ->
        server.stderr.on 'data', (data) ->
        server.on 'close', (code) ->
            console.log('[php-server] child process exited with code ' + code)
        done(true)###
