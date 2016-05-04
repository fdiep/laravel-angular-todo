(function() {
  'use strict';

  angular
  .module('App.directive')
  .directive('heading', heading);

  heading.$inject = [];

  function heading(){
    return {
      restrict: 'E',
      templateUrl: '/tpl/directives/title.html',
      transclude: true
    };
  }

}());
