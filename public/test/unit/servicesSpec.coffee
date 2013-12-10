describe 'service', ->
    beforeEach(module('gitWalrusApp'))

    describe 'md5', ->
        md5 = null

        beforeEach ->
            inject ($injector) ->
                console.log $injector
                md5 = $injector.get('md5')

        it 'should respond to generate', ->
            expect(typeof md5.generate).toBe 'function'

        it 'should convert md5', ->
            expect(md5.generate('Hello world')).toEqual('3e25960a79dbc69b674cd4ec67a72c62')

    describe 'gravatar', ->
        gravatar = md5Mock = null
        beforeEach ->
            md5Mock = { generate: '' }
            module ($provide) ->
                $provide.service 'md5', md5Mock
            inject ($injector) ->
                console.log $injector
                gravatar = $injector.get('gravatar')

        it 'should be true', ->
            expect(true).toBeTruthy()

#        it 'should respond to generate', ->
#            expect(typeof gravatar.generate).toBe 'function'
#            #expect(mockMd5.generate).not.toHaveBeenCalled();

#        it 'should create an gravatar url for an email address', ->
#            expect(gravatar.generate('test@mail.com')).toEqual('')
