(function() {
  'use strict';

  angular
    .module('App.directive')
    .directive('alerts', alerts);

  alerts.$inject = [
    '$log',
    'AlertService'
  ];

  function alerts($log, AlertService) {
    return {
      restrict: 'E',
      templateUrl: '/tpl/directives/alerts.html',
      link: _link
    };

    function _link(scope) {
      scope.alerts = [];

      scope.closeAlert = function(index) {
        AlertService.remove(index);
      };

      scope.$on('AlertService::alertAdded', refreshAlerts);
      scope.$on('AlertService::alertRemoved', refreshAlerts);
      refreshAlerts();

      function refreshAlerts(){
        scope.alerts = AlertService.getAlerts();
        $log.info('[alertsDirective::refreshAlerts]', scope.alerts);
      }
    }
  }

}());
