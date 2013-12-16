(function() {
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

}).call(this);

(function() {
  describe('Filters: ', function() {
    beforeEach(module("gitWalrusApp"));
    describe('strip_last_tree_portion', function() {
      return it('should remove the last part of a tree path', inject(function($filter) {
        var filter;
        filter = $filter('strip_last_tree_portion');
        expect(filter).not.toEqual(null);
        expect(filter('test')).toEqual('test');
        expect(filter('test/test2')).toEqual('test');
        return expect(filter('test/test2/test3')).toEqual('test/test2');
      }));
    });
    describe('gravatar', function() {
      return it('should create gravatars', inject(function($filter) {
        var filter;
        filter = $filter('gravatar');
        return expect(filter).not.toEqual(null);
      }));
    });
    return describe('title', function() {
      return it('should create title', inject(function($filter) {
        var filter;
        filter = $filter('title');
        expect(filter).not.toEqual(null);
        expect(filter('test')).toEqual('Test');
        expect(filter('test test')).toEqual('Test Test');
        return expect(filter('test test 2 i')).toEqual('Test Test 2 I');
      }));
    });
  });

}).call(this);

(function() {
  describe('Services: ', function() {
    beforeEach(module('gitWalrusApp'));
    describe('md5', function() {
      var md5;
      md5 = null;
      beforeEach(function() {
        return inject(function($injector) {
          return md5 = $injector.get('md5');
        });
      });
      it('should respond to generate', function() {
        return expect(typeof md5.generate).toBe('function');
      });
      return it('should convert md5', function() {
        return expect(md5.generate('Hello world')).toEqual('3e25960a79dbc69b674cd4ec67a72c62');
      });
    });
    return describe('gravatar', function() {
      var gravatar;
      gravatar = null;
      beforeEach(function() {
        return inject(function($injector) {
          return gravatar = $injector.get('gravatar');
        });
      });
      it('should work', function() {
        return expect(true).toBeTruthy();
      });
      it('should respond to generate', function() {
        return expect(typeof gravatar.generate).toBe('function');
      });
      it('should create an gravatar url for an email address', function() {
        return expect(gravatar.generate('test@mail.com')).toEqual('http://www.gravatar.com/avatar/97dfebf4098c0f5c16bca61e2b76c373?s=50');
      });
      return it('should create an gravatar url for an email address and a size', function() {
        return expect(gravatar.generate('test@mail.com', 100)).toEqual('http://www.gravatar.com/avatar/97dfebf4098c0f5c16bca61e2b76c373?s=100');
      });
    });
  });

}).call(this);
