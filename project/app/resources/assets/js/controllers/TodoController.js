(function() {
  'use strict';

  angular
    .module('App.controller')
    .controller('TodoController', TodoController);

  TodoController.$inject = [
    '$log',
    '$state',
    '$http',
    '$rootScope',
    '$scope',
    '$auth'
  ];

  function TodoController(
    $log,
    $state,
    $http,
    $rootScope,
    $scope,
    $auth
  ) {
    $log.debug('[TodoController]');

    $scope.todos = [];
    $scope.newTodo = {};

    $scope.init = function() {

      $http.get('/api/v1/todo').success(function(response) {
        $log.info('[TodoController::init]', response);
        $scope.todos = response.data;
      });
    };

    $scope.save = function() {
      $http.post('/api/v1/todo', $scope.newTodo).success(function(response) {
        $log.info('[TodoController::save]', response);
        $scope.todos.push(response.data);
        $scope.newTodo = {};
      });
    };

    $scope.update = function(index) {
      $log.info('[TodoController::update]', index);
      $http.put('/api/v1/todo/' + $scope.todos[index].id, $scope.todos[index]);
    };

    $scope.delete = function(index) {
      $log.info('[TodoController::delete]', index);
      $http.delete('/api/v1/todo/' + $scope.todos[index].id).success(function() {
        $scope.todos.splice(index, 1);
      });
    };

    $scope.logout = function() {
      $log.info('[TodoController::logout]');
      $auth.logout().then(function() {
        $rootScope.currentUser = null;
        $state.go('login');
      });
    };

    $scope.init();
  }

}());
