'use strict'

describe 'HomepageController', ->
    it 'should add 1 and 1', ->
        browser().navigateTo('/')
        expect(browser().location().url()).toBe('/')
