(function() {
  'use strict';

  angular
  .module('App.controller')
  .controller('MainController', MainController);

  MainController.$inject = [
    '$log'
  ];

  function MainController($log){
    $log.debug('[MainController]');
  }

}());
