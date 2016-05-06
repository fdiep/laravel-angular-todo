<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomePageTest extends TestCase
{
    /**
     * Make sure angular app and main controller are present
     *
     * @return void
     */
    public function testHomePage()
    {
        $this->visit('/')
             ->see('Todo App')
             ->see('ng-app="TodoApp"')
             ->see('ng-controller="MainController"');
    }
}
