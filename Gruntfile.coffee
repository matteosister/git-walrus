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
        watch:
            coffee:
                files: ['Gruntfile.coffee', 'public/coffee/**/*.coffee']
                tasks: ['coffee']
            coffee_unit:
                files: ['Gruntfile.coffee', 'public/coffee/**/*.coffee']
                tasks: ['coffee', 'karma:unit']
            css:
                files: ['public/compass/sass/*.scss', 'public/compass/sass/**/*.scss']
                tasks: ['compass']
        coffee:
            compileWithMaps:
                options:
                    sourceMap: true
                files:
                    'public/js/git-walrus.js': ['public/coffee/walrus/*.coffee', 'public/coffee/walrus/**/*.coffee']
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
            options:
                logConcurrentOutput: true
            test: ['unit', 'protractor:chrome']
            watch_assets: ['watch:css', 'watch:coffee']
        shell:
            phantom_webdriver:
                command: 'node_modules/.bin/phantomjs --webdriver=4444'
                options:
                    stdout: false
                    stderr: false
                    async: true
            phpserver:
                command: '/usr/bin/php app/console.php server:run'
                options:
                    stdout: false
                    stderr: false
                    async: true
        open:
            dev:
              path: 'http://127.0.0.1:8000/',
              app: 'google-chrome'

    grunt.loadNpmTasks 'grunt-contrib-watch'
    grunt.loadNpmTasks 'grunt-contrib-coffee'
    grunt.loadNpmTasks 'grunt-contrib-compass'
    grunt.loadNpmTasks 'grunt-karma'
    grunt.loadNpmTasks 'grunt-notify'
    grunt.loadNpmTasks 'grunt-php'
    grunt.loadNpmTasks 'grunt-concurrent'
    grunt.loadNpmTasks 'grunt-shell-spawn'
    grunt.loadNpmTasks 'grunt-open'

    grunt.registerTask 'default', ['test']
    grunt.registerTask 'unit', ['coffee', 'karma:unit']
    grunt.registerTask 'unit:watch', ['coffee', 'watch:coffee_unit']
    grunt.registerTask 'test', ['coffee', 'php:test', 'karma:unit']
    grunt.registerTask 'travis', ['coffee', 'karma:travis']
    grunt.registerTask 'serve', ['assets', 'shell:phpserver', 'open', 'concurrent:watch_assets']
    grunt.registerTask 'assets', ['coffee', 'compass:dev']
