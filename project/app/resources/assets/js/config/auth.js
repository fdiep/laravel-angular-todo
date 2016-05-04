(function() {
  'use strict';

  angular.module('ScannerApp')
    .config(function($authProvider) {
      // set api path to authenticate
      $authProvider.loginUrl = '/api/v1/authenticate';
      $authProvider.tokenRoot = 'data';
    });
}());
