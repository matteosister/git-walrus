(function() {
  'use strict';
  describe('HomepageController', function() {
    it('should add a clock to the page', function() {
      var clock;
      browser.get('http://localhost:8000');
      clock = element(findBy.binding('date'));
      expect(clock).toBeDefined();
      return expect(clock.getText()).toMatch(/\d{1,2}\:\d{1,2}\:\d{1,2}\s[A|P]M/);
    });
    it('should display a list of branches', function() {
      var ulBranches;
      browser.get('http://localhost:8000');
      ulBranches = element(findBy.css('.branches ul.list-group'));
      expect(ulBranches.isPresent()).toBeTruthy();
      return expect(ulBranches.isDisplayed()).toBeTruthy();
    });
    it('should display a list of logs', function() {
      var ulLogs;
      browser.get('http://localhost:8000');
      ulLogs = element(findBy.css('.log ul.list-group'));
      expect(ulLogs.isPresent()).toBeTruthy();
      return expect(ulLogs.isDisplayed()).toBeTruthy();
    });
    return it('should allows to go back to homepage by clicking on the logo', function() {});
  });

}).call(this);
