'use strict'

spawn = require('child_process').spawn

module.exports = (grunt) ->
    grunt.initConfig
        pkg: grunt.file.readJSON 'package.json'
        protractor:
            options:
                configFile: "public/config/protractor.conf.js"
                keepAlive: true # If false, the grunt process stops when the test fails.
                noColor: false # If true, protractor will not use colors in its output.
                specs: ["public/test/e2e/homepage.js"]
            homepage:
                options:
                    args: {}
        watch:
            coffee:
                files: ['Gruntfile.coffee', 'public/coffee/**/*.coffee']
                tasks: ['coffee', 'karma']
            css:
                files: ['public/compass/sass/*.scss']
                tasks: ['compass']
            protractor_configuration:
                files: ['public/config/protractor.conf.js']
                tasks: ['protractor']
            karma_configuration:
                files: ['public/config/karma.conf.js']
                tasks: ['karma']
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

    grunt.loadNpmTasks 'grunt-contrib-watch'
    grunt.loadNpmTasks 'grunt-contrib-coffee'
    grunt.loadNpmTasks 'grunt-contrib-compass'
    grunt.loadNpmTasks 'grunt-protractor-runner'
    grunt.loadNpmTasks 'grunt-karma'
    grunt.loadNpmTasks 'grunt-notify'

    grunt.registerTask 'default', ['php-server', 'watch']
    grunt.registerTask 'php-server', 'start a php server instance', () ->
        done = @async()
        server = spawn('/usr/bin/php', ['-S', 'localhost:8000', '-t', 'public/', 'public/dev.php'])
        server.stdout.on 'data', (data) ->
        server.stderr.on 'data', (data) ->
        server.on 'close', (code) ->
            console.log('[php-server] child process exited with code ' + code)
        done(true)
