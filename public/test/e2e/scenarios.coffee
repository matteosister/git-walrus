'use strict'

describe 'HomepageController', ->
    it 'should add a clock to the page', ->
        browser.get 'http://localhost:8000'
        clock = element(findBy.binding('date'))
        expect(clock).toBeDefined()
        expect(clock.getText()).toMatch(/\d{2}\:\d{2}\:\d{2}\sPM/)

    it 'should display a list of branches', ->
        browser.get 'http://localhost:8000'
        ulBranches = element(findBy.css '.branches ul.list-group')
        expect(ulBranches.isPresent()).toBeTruthy()
        expect(ulBranches.isDisplayed()).toBeTruthy()

