describe 'Controllers', ->
    beforeEach(module('gitWalrusApp'))

#    describe 'HomepageController', ->
#        scope = null
#        ctrl = null
#        $httpBackend = null
#
#        beforeEach inject((_$httpBackend_, $rootScope, $controller) ->
#            $injector = angular.injector(['ng', 'ngResource']);
#            resource = $injector.get('$resource');
#            $httpBackend = _$httpBackend_;
#            $httpBackend.when('GET', '/api/status/working-tree')
#                .respond('{}')
#            $httpBackend.when('GET', '/api/status/index')
#                .respond('{}')
#            scope = $rootScope.$new();
#            ctrl = $controller('HomepageController', {$scope: scope, $resource: resource});
#        )
#
#        it 'should have a working_tree var', ->
#            expect(scope.working_tree).toBeUndefined()
#            $httpBackend.flush();
#            expect(scope.working_tree.length).toBe(1)


    describe 'LogController', ->
        scope = null
        $httpBackend = null
        ctrl = null
        beforeEach inject((_$httpBackend_, $rootScope, $controller) ->
            $injector = angular.injector(['ng']);
            $httpBackend = _$httpBackend_;
            $httpBackend.when('GET', '/api/branches')
                .respond('[{"name":"master"}]')
            $httpBackend.when('GET', '/api/log/master')
                .respond('{"commits":[{"name": "foo"}]}')
            scope = $rootScope.$new();
            ctrl = $controller('LogController', {$scope: scope});
        )

        it 'should create "branches" in the scope with at least one object', ->
            expect(scope.branches).toBeUndefined()
            $httpBackend.flush();
            expect(scope.branches.length).toBe(1)
            expect(scope.branches[0].name).toBe('master')

        it 'should create "logs" in the scope with at least one object', ->
            expect(scope.logs).toBeUndefined()
            $httpBackend.flush();
            expect(scope.logs.length).toBe(1)
            expect(scope.logs[0].name).toBe('foo')

        it 'should have a selected_log null', ->
            expect(scope.selected_log).toBeUndefined()


    describe 'TreeController', ->
        scope = null
        ctrl = null
        $httpBackend = null

        beforeEach inject((_$httpBackend_, $rootScope, $controller) ->
            $httpBackend = _$httpBackend_;
            $httpBackend.expectGET('/api/tree/master')
                .respond("""
                    {
                        "ref":"master",
                        "children":[
                            {"type":"tree","sha":"123","name":"test_tree"},
                            {"type":"blob","sha":"456","name":"test_blob"}
                        ]
                    }
                """)
            scope = $rootScope.$new();
            ctrl = $controller('TreeController', {$scope: scope, $location: { path: () -> '/tree/master' }});
        )

        it 'should attach a tree to the scope', ->
            expect(scope.tree).toBeUndefined()
            $httpBackend.flush();
            expect(scope.tree).toBeDefined()

        it 'should attach a path to the scope', ->
            expect(scope.path).toBeUndefined()
            $httpBackend.flush();
            expect(scope.path).toBeDefined()