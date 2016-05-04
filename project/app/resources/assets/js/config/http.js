(function() {
  'use strict';

  angular.module('ScannerApp')
    .config(function($injector, $httpProvider) {
      $httpProvider.interceptors.push(function($injector, $q) {
        return {
          // override response data when making calls to API
          response: function(response) {
            if(response.config.url.startsWith('/api/')){
              response.data.data = response.data.data.data;
            }
            return response;
          },
          // redirect to login on 401 code
          responseError: function(rejection) {
            console.warn('[http::responseError]', rejection);
            var $state = $injector.get('$state');
            var rejectionCode = 403;

            if (rejection.status === rejectionCode) {
              $state.go('login');
            }

            return $q.reject(rejection);
          }
        };
      });
    });
}());
