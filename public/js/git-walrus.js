(function() {
  "use strict";
  var gitWalrusApp;

  gitWalrusApp = angular.module('gitWalrusApp', ['ngRoute', 'gitWalrusFilters', 'angular-underscore']);

  gitWalrusApp.config([
    '$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
      $routeProvider.when('/', {
        templateUrl: '/partials/homepage.html',
        controller: 'HomepageController'
      }).when('/tree/:ref*', {
        templateUrl: '/partials/tree.html',
        controller: 'TreeController'
      }).otherwise({
        redirectTo: '/'
      });
      return $locationProvider.html5Mode(true);
    }
  ]);

  'use strict';

  gitWalrusApp.controller('HomepageController', function($scope, $http, $interval) {
    var updateDate;
    $http.get('/api/branches').success(function(data) {
      return $scope.branches = data.items;
    });
    $http.get('/api/log/master').success(function(data) {
      return $scope.logs = data.items;
    });
    $scope.date = new Date();
    updateDate = function() {
      return $scope.date = new Date();
    };
    return $interval(updateDate, 1000);
  });

  gitWalrusApp.controller('TreeController', function($scope, $http, $location, syntaxHighlighter) {
    return $http.get("/api" + ($location.path())).success(function(data) {
      $scope.tree = data;
      return $scope.path = $location.path();
    });
  });

  angular.module('gitWalrusFilters', []).filter('strip_last_tree_portion', function() {
    return function(input) {
      var lastOccurrence;
      if (input == null) {
        return input;
      }
      lastOccurrence = input.lastIndexOf('/');
      if (lastOccurrence === -1) {
        return input;
      }
      return input.substr(0, lastOccurrence);
    };
  }).filter('gravatar', function(gravatar) {
    return function(email, size) {
      if (size == null) {
        size = 50;
      }
      return gravatar.generate(email, size);
    };
  }).filter('title', function() {
    return function(value) {
      var arr;
      arr = value.split(' ');
      arr = _.map(arr, function(v) {
        return v.charAt(0).toUpperCase() + v.slice(1).toLowerCase();
      });
      return arr.join(' ');
    };
  });

  gitWalrusApp.factory('syntaxHighlighter', function() {
    var service;
    return service = {
      highlight: function() {
        return SyntaxHighlighter.all();
      }
    };
  });

  gitWalrusApp.factory('gravatar', [
    'md5', function(md5) {
      return {
        generate: function(email, size) {
          var emailHash;
          if (size == null) {
            size = 50;
          }
          emailHash = md5.generate(email);
          return "http://www.gravatar.com/avatar/" + emailHash + "?s=" + size;
        }
      };
    }
  ]);

  gitWalrusApp.factory('md5', function() {
    return {
      generate: function(value) {
        var add32, cmn, ff, gg, hex, hex_chr, hh, ii, md5, md51, md5blk, md5cycle, rhex;
        md5cycle = function(x, k) {
          var a, b, c, d;
          a = x[0];
          b = x[1];
          c = x[2];
          d = x[3];
          a = ff(a, b, c, d, k[0], 7, -680876936);
          d = ff(d, a, b, c, k[1], 12, -389564586);
          c = ff(c, d, a, b, k[2], 17, 606105819);
          b = ff(b, c, d, a, k[3], 22, -1044525330);
          a = ff(a, b, c, d, k[4], 7, -176418897);
          d = ff(d, a, b, c, k[5], 12, 1200080426);
          c = ff(c, d, a, b, k[6], 17, -1473231341);
          b = ff(b, c, d, a, k[7], 22, -45705983);
          a = ff(a, b, c, d, k[8], 7, 1770035416);
          d = ff(d, a, b, c, k[9], 12, -1958414417);
          c = ff(c, d, a, b, k[10], 17, -42063);
          b = ff(b, c, d, a, k[11], 22, -1990404162);
          a = ff(a, b, c, d, k[12], 7, 1804603682);
          d = ff(d, a, b, c, k[13], 12, -40341101);
          c = ff(c, d, a, b, k[14], 17, -1502002290);
          b = ff(b, c, d, a, k[15], 22, 1236535329);
          a = gg(a, b, c, d, k[1], 5, -165796510);
          d = gg(d, a, b, c, k[6], 9, -1069501632);
          c = gg(c, d, a, b, k[11], 14, 643717713);
          b = gg(b, c, d, a, k[0], 20, -373897302);
          a = gg(a, b, c, d, k[5], 5, -701558691);
          d = gg(d, a, b, c, k[10], 9, 38016083);
          c = gg(c, d, a, b, k[15], 14, -660478335);
          b = gg(b, c, d, a, k[4], 20, -405537848);
          a = gg(a, b, c, d, k[9], 5, 568446438);
          d = gg(d, a, b, c, k[14], 9, -1019803690);
          c = gg(c, d, a, b, k[3], 14, -187363961);
          b = gg(b, c, d, a, k[8], 20, 1163531501);
          a = gg(a, b, c, d, k[13], 5, -1444681467);
          d = gg(d, a, b, c, k[2], 9, -51403784);
          c = gg(c, d, a, b, k[7], 14, 1735328473);
          b = gg(b, c, d, a, k[12], 20, -1926607734);
          a = hh(a, b, c, d, k[5], 4, -378558);
          d = hh(d, a, b, c, k[8], 11, -2022574463);
          c = hh(c, d, a, b, k[11], 16, 1839030562);
          b = hh(b, c, d, a, k[14], 23, -35309556);
          a = hh(a, b, c, d, k[1], 4, -1530992060);
          d = hh(d, a, b, c, k[4], 11, 1272893353);
          c = hh(c, d, a, b, k[7], 16, -155497632);
          b = hh(b, c, d, a, k[10], 23, -1094730640);
          a = hh(a, b, c, d, k[13], 4, 681279174);
          d = hh(d, a, b, c, k[0], 11, -358537222);
          c = hh(c, d, a, b, k[3], 16, -722521979);
          b = hh(b, c, d, a, k[6], 23, 76029189);
          a = hh(a, b, c, d, k[9], 4, -640364487);
          d = hh(d, a, b, c, k[12], 11, -421815835);
          c = hh(c, d, a, b, k[15], 16, 530742520);
          b = hh(b, c, d, a, k[2], 23, -995338651);
          a = ii(a, b, c, d, k[0], 6, -198630844);
          d = ii(d, a, b, c, k[7], 10, 1126891415);
          c = ii(c, d, a, b, k[14], 15, -1416354905);
          b = ii(b, c, d, a, k[5], 21, -57434055);
          a = ii(a, b, c, d, k[12], 6, 1700485571);
          d = ii(d, a, b, c, k[3], 10, -1894986606);
          c = ii(c, d, a, b, k[10], 15, -1051523);
          b = ii(b, c, d, a, k[1], 21, -2054922799);
          a = ii(a, b, c, d, k[8], 6, 1873313359);
          d = ii(d, a, b, c, k[15], 10, -30611744);
          c = ii(c, d, a, b, k[6], 15, -1560198380);
          b = ii(b, c, d, a, k[13], 21, 1309151649);
          a = ii(a, b, c, d, k[4], 6, -145523070);
          d = ii(d, a, b, c, k[11], 10, -1120210379);
          c = ii(c, d, a, b, k[2], 15, 718787259);
          b = ii(b, c, d, a, k[9], 21, -343485551);
          x[0] = add32(a, x[0]);
          x[1] = add32(b, x[1]);
          x[2] = add32(c, x[2]);
          return x[3] = add32(d, x[3]);
        };
        cmn = function(q, a, b, x, s, t) {
          a = add32(add32(a, q), add32(x, t));
          return add32((a << s) | (a >>> (32 - s)), b);
        };
        ff = function(a, b, c, d, x, s, t) {
          return cmn((b & c) | ((~b) & d), a, b, x, s, t);
        };
        gg = function(a, b, c, d, x, s, t) {
          return cmn((b & d) | (c & (~d)), a, b, x, s, t);
        };
        hh = function(a, b, c, d, x, s, t) {
          return cmn(b ^ c ^ d, a, b, x, s, t);
        };
        ii = function(a, b, c, d, x, s, t) {
          return cmn(c ^ (b | (~d)), a, b, x, s, t);
        };
        md51 = function(s) {
          var i, n, state, tail, txt;
          txt = "";
          n = s.length;
          state = [1732584193, -271733879, -1732584194, 271733878];
          i = void 0;
          i = 64;
          while (i <= s.length) {
            md5cycle(state, md5blk(s.substring(i - 64, i)));
            i += 64;
          }
          s = s.substring(i - 64);
          tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
          i = 0;
          while (i < s.length) {
            tail[i >> 2] |= s.charCodeAt(i) << ((i % 4) << 3);
            i++;
          }
          tail[i >> 2] |= 0x80 << ((i % 4) << 3);
          if (i > 55) {
            md5cycle(state, tail);
            i = 0;
            while (i < 16) {
              tail[i] = 0;
              i++;
            }
          }
          tail[14] = n * 8;
          md5cycle(state, tail);
          return state;
        };
        md5blk = function(s) {
          var i, md5blks;
          md5blks = [];
          i = void 0;
          i = 0;
          while (i < 64) {
            md5blks[i >> 2] = s.charCodeAt(i) + (s.charCodeAt(i + 1) << 8) + (s.charCodeAt(i + 2) << 16) + (s.charCodeAt(i + 3) << 24);
            i += 4;
          }
          return md5blks;
        };
        rhex = function(n) {
          var j, s;
          s = "";
          j = 0;
          while (j < 4) {
            s += hex_chr[(n >> (j * 8 + 4)) & 0x0F] + hex_chr[(n >> (j * 8)) & 0x0F];
            j++;
          }
          return s;
        };
        hex = function(x) {
          var i;
          i = 0;
          while (i < x.length) {
            x[i] = rhex(x[i]);
            i++;
          }
          return x.join("");
        };
        md5 = function(s) {
          return hex(md51(s));
        };
        add32 = function(a, b) {
          return (a + b) & 0xFFFFFFFF;
        };
        hex_chr = "0123456789abcdef".split("");
        if (md5("hello") !== "5d41402abc4b2a76b9719d911017c592") {
          add32 = function(x, y) {
            var lsw, msw;
            lsw = (x & 0xFFFF) + (y & 0xFFFF);
            msw = (x >> 16) + (y >> 16) + (lsw >> 16);
            return (msw << 16) | (lsw & 0xFFFF);
          };
        }
        return md5(value);
      }
    };
  });

}).call(this);

/*
//@ sourceMappingURL=git-walrus.js.map
*/