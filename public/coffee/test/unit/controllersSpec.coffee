describe 'Controllers', ->
    beforeEach(module('gitWalrusApp'))

    describe 'HomepageController', ->
        scope = null
        ctrl = null
        $httpBackend = null

        beforeEach inject((_$httpBackend_, $rootScope, $controller) ->
            $httpBackend = _$httpBackend_;
            $httpBackend.expectGET('/api/branches')
                .respond('[{"name":"master"}]')
            $httpBackend.expectGET('/api/log/master?context=detail')
                .respond('{"commits":[{"message":"test message"}]}')
            scope = $rootScope.$new();
            ctrl = $controller('HomepageController', {$scope: scope});
        )

        it 'should create "branches" in the scope with at least one object', ->
            expect(scope.branches).toBeUndefined()
            expect(scope.logs).toBeUndefined()

            $httpBackend.flush();

            expect(scope.branches.length).toBe(1)
            expect(scope.branches[0].name).toBe('master')
            expect(scope.logs.length).toBe(1)
            expect(scope.logs[0].message).toBe('test message')

        it 'should attach a date', ->
            expect(scope.date).toBeDefined()
            expect(scope.date).toEqual(new Date())

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