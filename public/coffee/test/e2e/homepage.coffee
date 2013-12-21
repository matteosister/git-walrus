'use strict'

describe 'HomepageController', ->

    it 'should add a clock to the page', ->
        browser.get 'http://localhost:8000'
        clock = element(findBy.binding('date'))
        expect(clock).toBeDefined()
        expect(clock.getText()).toMatch(/\d{1,2}\:\d{1,2}\:\d{1,2}\s[A|P]M/)

    it 'should display a list of branches', ->
        browser.get 'http://localhost:8000'
        ulBranches = element(findBy.css '.branches ul.list-group')
        expect(ulBranches.isPresent()).toBeTruthy()
        expect(ulBranches.isDisplayed()).toBeTruthy()

    it 'should display a list of logs', ->
        browser.get 'http://localhost:8000'
        ulLogs = element(findBy.css '.logs')
        expect(ulLogs.isPresent()).toBeTruthy()
        expect(ulLogs.isDisplayed()).toBeTruthy()

    it 'should show a tree of files', ->
        browser.get 'http://localhost:8000'
        treeLink = element(findBy.linkText 'browse tree')
        treeLink.click ->
            expect(browser.getCurrentUrl()).toBe '..'


    it 'should allows to go back to homepage by clicking on the logo', ->
        browser.get 'http://localhost:8000/tree/master'

