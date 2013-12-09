// Generated by CoffeeScript 1.6.3
describe('Controllers', function() {
  beforeEach(module('gitWalrusApp'));
  describe('HomepageController', function() {
    var $httpBackend, ctrl, scope;
    scope = null;
    ctrl = null;
    $httpBackend = null;
    beforeEach(inject(function(_$httpBackend_, $rootScope, $controller) {
      $httpBackend = _$httpBackend_;
      $httpBackend.expectGET('/api/branches').respond('{"items":[{"name":"master"}]}');
      $httpBackend.expectGET('/api/log/master').respond('{"items":[{"message":"test message"}]}');
      scope = $rootScope.$new();
      return ctrl = $controller('HomepageController', {
        $scope: scope
      });
    }));
    it('should create "branches" in the scope with at least one object', function() {
      expect(scope.branches).toBeUndefined();
      expect(scope.logs).toBeUndefined();
      $httpBackend.flush();
      expect(scope.branches.length).toBe(1);
      expect(scope.branches[0].name).toBe('master');
      expect(scope.logs.length).toBe(1);
      return expect(scope.logs[0].message).toBe('test message');
    });
    return it('should attach a date', function() {
      expect(scope.date).toBeDefined();
      return expect(scope.date).toEqual(new Date());
    });
  });
  return describe('TreeController', function() {
    var $httpBackend, ctrl, scope;
    scope = null;
    ctrl = null;
    $httpBackend = null;
    beforeEach(inject(function(_$httpBackend_, $rootScope, $controller) {
      $httpBackend = _$httpBackend_;
      $httpBackend.expectGET('/api/tree/master').respond("{\n    \"ref\":\"master\",\n    \"children\":[\n        {\"type\":\"tree\",\"sha\":\"123\",\"name\":\"test_tree\"},\n        {\"type\":\"blob\",\"sha\":\"456\",\"name\":\"test_blob\"}\n    ]\n}");
      scope = $rootScope.$new();
      return ctrl = $controller('TreeController', {
        $scope: scope,
        $location: {
          path: function() {
            return '/tree/master';
          }
        }
      });
    }));
    it('should attach a tree to the scope', function() {
      expect(scope.tree).toBeUndefined();
      $httpBackend.flush();
      return expect(scope.tree).toBeDefined();
    });
    return it('should attach a path to the scope', function() {
      expect(scope.path).toBeUndefined();
      $httpBackend.flush();
      return expect(scope.path).toBeDefined();
    });
  });
});