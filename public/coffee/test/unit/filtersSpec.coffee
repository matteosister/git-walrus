describe 'Filters: ', ->
    beforeEach module("gitWalrusApp")
    describe 'strip_last_tree_portion', ->
        it 'should remove the last part of a tree path', inject ($filter) ->
            filter = $filter('strip_last_tree_portion')
            expect(filter).not.toEqual(null)
            expect(filter('test')).toEqual('test')
            expect(filter('test/test2')).toEqual('test')
            expect(filter('test/test2/test3')).toEqual('test/test2')

    describe 'gravatar', ->
        it 'should create gravatars', inject ($filter) ->
            filter = $filter('gravatar')
            expect(filter).not.toEqual(null)

    describe 'title', ->
        it 'should create title', inject ($filter) ->
            filter = $filter('title')
            expect(filter).not.toEqual(null)
            expect(filter('test')).toEqual('Test')
            expect(filter('test test')).toEqual('Test Test')
            expect(filter('test test 2 i')).toEqual('Test Test 2 I')
