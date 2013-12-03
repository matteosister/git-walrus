// Generated by CoffeeScript 1.6.3
'use strict';
describe('HomepageController', function() {
  it('should add a clock to the page', function() {
    var clock;
    browser.get('http://localhost:8000');
    clock = element(findBy.binding('date'));
    expect(clock).toBeDefined();
    return expect(clock.getText()).toMatch(/\d{2}\:\d{2}\:\d{2}\sPM/);
  });
  return it('should display a list of branches', function() {
    var ulBranches;
    browser.get('http://localhost:8000');
    ulBranches = element(findBy.css('.branches ul.list-group'));
    expect(ulBranches.isPresent()).toBeTruthy();
    return expect(ulBranches.isDisplayed()).toBeTruthy();
  });
});
