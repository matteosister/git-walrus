gitWalrusApp.factory 'gravatar', ['md5', (md5) ->
    generate: (email, size = 80) ->
        emailHash = md5.generate email
        "http://www.gravatar.com/avatar/#{ emailHash }?s=#{ size }"
]