<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{

  /**
   * Auth Urls
   */

   private $registerUrl;
   private $authUrl;
   private $userUrl;

   /**
    * Build all urls
    */
   function __construct() {
      $this->registerUrl = $this->apiUrl . '/register';
      $this->authUrl = $this->apiUrl . '/authenticate';
      $this->userUrl = $this->authUrl . '/user';
   }

    /**
     * Test registration with valid user payload.
     */
    public function testRegistration()
    {
        $user = factory(App\User::class)->make();
        $payload = [
          'name' => $user->name,
          'email' => $user->email,
          'password' => $user->password
        ];

        $this->json('POST', $this->registerUrl, $payload)
             ->seeJson([
                 'name' => $user->name,
                 'email' => $user->email,
             ]);
    }
}
