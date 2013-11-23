describe 'Controllers', ->
    beforeEach(module('gitWalrusApp'))

    describe 'HomepageController', ->
        scope = null
        ctrl = null
        $httpBackend = null

        beforeEach inject((_$httpBackend_, $rootScope, $controller) ->
            $httpBackend = _$httpBackend_;
            $httpBackend.expectGET('/api/branches')
                .respond('{"items":[{"name":"master"}]}')
            $httpBackend.expectGET('/api/log/master')
                .respond('{"items":[{"message":"test message"}]}')
            scope = $rootScope.$new();
            ctrl = $controller('HomepageController', {$scope: scope});
        )

        it 'should create "branches" in the scope with at least one object', ->
            expect(scope.branches).toBeUndefined()
            expect(scope.log).toBeUndefined()

            $httpBackend.flush();

            expect(scope.branches.length).toBe(1)
            expect(scope.branches[0].name).toBe('master')
            expect(scope.log.length).toBe(1)
            expect(scope.log[0].message).toBe('test message')

        it 'should attach a date', ->
            expect(scope.date).toBeDefined()
            expect(scope.date).toEqual(new Date())