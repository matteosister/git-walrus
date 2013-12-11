describe 'Services: ', ->
    beforeEach(module('gitWalrusApp'))

    describe 'md5', ->
        md5 = null

        beforeEach ->
            inject ($injector) ->
                md5 = $injector.get('md5')

        it 'should respond to generate', ->
            expect(typeof md5.generate).toBe 'function'

        it 'should convert md5', ->
            expect(md5.generate('Hello world')).toEqual('3e25960a79dbc69b674cd4ec67a72c62')

    describe 'gravatar', ->
        gravatar = null

        beforeEach ->
            inject ($injector) ->
                gravatar = $injector.get('gravatar')

        it 'should work', ->
            expect(true).toBeTruthy()

        it 'should respond to generate', ->
            expect(typeof gravatar.generate).toBe 'function'

        it 'should create an gravatar url for an email address', ->
            expect(gravatar.generate 'test@mail.com')
                .toEqual('http://www.gravatar.com/avatar/97dfebf4098c0f5c16bca61e2b76c373?s=50')

        it 'should create an gravatar url for an email address and a size', ->
            expect(gravatar.generate 'test@mail.com', 100)
                .toEqual('http://www.gravatar.com/avatar/97dfebf4098c0f5c16bca61e2b76c373?s=100')
