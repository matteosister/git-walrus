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
            files: ['Gruntfile.coffee', 'public/coffee/**/*.coffee']
            tasks: ['coffee', 'protractor']
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

    grunt.loadNpmTasks 'grunt-contrib-watch'
    grunt.loadNpmTasks 'grunt-contrib-coffee'
    grunt.loadNpmTasks 'grunt-protractor-runner'

    grunt.registerTask 'default', ['watch']