(function() {
  'use strict';

  angular
    .module('App.factory')
    .factory('AlertService', alertService);

  alertService.$inject = [
    '$log',
    '$rootScope'
  ];

  function alertService($log, $rootScope) {
    var _self = {};

    // broadcast event names
    _self.BROADCAST_ADD = 'AlertService::alertAdded';
    _self.BROADCAST_REMOVE = 'AlertService::alertRemoved';

    // create an array of alerts
    _self.alerts = [];

    _self.error = function(msg) {
      $log.info('[AlertService::error]', msg);
      _self.add('error', msg);
    };

    _self.success = function(msg) {
      $log.info('[AlertService::success]', msg);
      _self.add('success', msg);
    };

    _self.add = function(type, msg) {
      $log.info('[AlertService::add]', type, msg);
      _self.alerts.push({
        'type': type,
        'msg': msg
      });
      $rootScope.$broadcast(_self.BROADCAST_ADD);
    };

    _self.remove = function(index) {
      $log.info('[AlertService::remove]', index);
      _self.alerts.splice(index, 1);
      $rootScope.$broadcast(_self.BROADCAST_REMOVE);
    };

    _self.removeAll = function() {
      $log.info('[AlertService::removeAll]');
      _self.alerts = [];
      $rootScope.$broadcast(_self.BROADCAST_REMOVE);
    };

    _self.getAlerts = function(){
      return _self.alerts;
    };

    return _self;
  }

}());
