(function() {
  'use strict';

  angular
    .module('App.controller')
    .controller('AuthController', AuthController);

  AuthController.$inject = [
    '$log',
    '$auth',
    '$state',
    '$http',
    '$rootScope',
    '$scope',
    'AlertService'
  ];

  function AuthController(
    $log,
    $auth,
    $state,
    $http,
    $rootScope,
    $scope,
    AlertService
  ) {
    $log.debug('[AuthController]');

    $scope.email = '';
    $scope.password = '';
    $scope.newUser = {};

    $scope.login = function() {
      // remove all alerts
      AlertService.removeAll();

      var credentials = {
        email: $scope.email,
        password: $scope.password
      };
      $auth.login(credentials).then(function() {
        $scope.getAuthUser();
      }, function(error) {
        $log.warn('[AuthController::login]', error);
        AlertService.error(error.data.message);
      });
    };

    $scope.register = function() {
      // remove all alerts
      AlertService.removeAll();
      $http.post('/api/v1/register', $scope.newUser)
        .then(function(data) {
          $log.info('[AuthController::register]', data);
          $scope.email = $scope.newUser.email;
          $scope.password = $scope.newUser.password;
          $scope.login();
        }, function(error) {
          $log.warn('[AuthController::register]', error);
          AlertService.error(error.data.message);
        });
    };

    $scope.getAuthUser = function() {
      $http.get('/api/v1/authenticate/user').then(function(response) {
        $log.info('[AuthController::getAuthUser]', response);
        $rootScope.currentUser = response.data.data;
        $log.info('[AuthController::currentUser]', $rootScope.currentUser);

        $state.go('todo');
      });
    };

    function init(){
      // remove all alerts
      AlertService.removeAll();
    }

    // @TODO: place in a directive
    $('.ui.form').form({
      onSuccess: function(){
        // prevent form submission. Let angular take over
        return false;
      },
      fields: {
        name: {
          identifier: 'name',
          rules: [{
            type: 'empty',
            prompt: 'Please enter your name'
          }]
        },
        email: {
          identifier: 'email',
          rules: [{
            type: 'empty',
            prompt: 'Please enter your e-mail'
          }, {
            type: 'email',
            prompt: 'Please enter a valid e-mail'
          }]
        },
        password: {
          identifier: 'password',
          rules: [{
            type: 'empty',
            prompt: 'Please enter your password'
          }, {
            type: 'length[6]',
            prompt: 'Your password must be at least 6 characters'
          }]
        }
      }
    });

    init();

  }

}());
