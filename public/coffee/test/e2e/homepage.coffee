'use strict'

describe 'HomepageController', ->
    it 'should add a clock to the page', ->
        browser.get 'http://localhost:8000'
        clock = element(findBy.binding('date'))
        expect(clock).toBeDefined()
        expect(clock.getText()).toMatch(/\d{1,2}\:\d{1,2}\:\d{1,2}\s[A|P]M/)


describe 'LogController', ->
    it 'should display a list of branches', ->
        browser.get 'http://localhost:8000/log'
        ulBranches = element(findBy.css '.branches ul.list-group')
        expect(ulBranches.isPresent()).toBeTruthy()
        expect(ulBranches.isDisplayed()).toBeTruthy()

    it 'should display a list of logs', ->
        browser.get 'http://localhost:8000/log'
        ulLogs = element(findBy.css '.logs')
        expect(ulLogs.isPresent()).toBeTruthy()
        expect(ulLogs.isDisplayed()).toBeTruthy()

    it 'should show a tree of files', ->
        browser.get 'http://localhost:8000/log'
        treeLink = element(findBy.linkText 'browse master tree')
        treeLink.click().then ->
            expect(browser.getCurrentUrl()).toContain 'tree/master'

    it 'should show a diff by clicking on a link log', ->
        browser.get 'http://localhost:8000/log'
        logLink = element(findBy.css '.logs .list-group-item p a')
        diffColumn = element(findBy.css('.diff'))
        diffColumn.isElementPresent(findBy.css('.diff-object')).then (finded) ->
            expect(finded).toBeFalsy()
        logLink.click().then ->
            diffColumn.isElementPresent(findBy.css('.diff-object')).then (finded) ->
                expect(finded).toBeTruthy()

describe 'TreeController', ->
    it 'should allows to go back to homepage by clicking on the logo', ->
        browser.get 'http://localhost:8000/tree/master'
        mainLink = element(findBy.css 'h1.logo a')
        mainLink.click().then ->
            expect(browser.getCurrentUrl()).toBe 'http://localhost:8000/'


