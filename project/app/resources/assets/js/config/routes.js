(function() {
  'use strict';

  angular.module('TodoApp')
      .config(function($stateProvider, $urlRouterProvider) {

          $urlRouterProvider.otherwise('/login');

          $stateProvider
              .state('login', {
                  url: '/login',
                  templateUrl: '/tpl/login.html',
                  controller: 'AuthController'
              })
              .state('register', {
                  url: '/register',
                  templateUrl: '/tpl/register.html',
                  controller: 'AuthController'
              })
              .state('todo', {
              url: '/todo',
              templateUrl: '/tpl/todo.html',
              controller: 'TodoController'
          });

      });
}());
