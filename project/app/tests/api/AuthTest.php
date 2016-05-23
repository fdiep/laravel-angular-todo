<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\ErrorCode as ErrorCode;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

   /**
    * Auth Urls.
    */
    private $authUrl;
    private $userUrl;

   /**
    * Build all urls.
    */
     public function __construct()
     {
         $this->authUrl = $this->apiUrl.'/authenticate';
         $this->userUrl = $this->authUrl.'/user';
     }

    /**
     * Test authenticate with valid user payload.
     */
      public function testValidAuthentication()
      {
          $passwordRaw = str_random(10);
          $user = factory(App\User::class)->create([
            'password' => bcrypt($passwordRaw),
          ]);
          $payload = [
            'email' => $user->email,
            'password' => $passwordRaw,
          ];

          $this->json('POST', $this->authUrl, $payload)
            ->seeJsonStructure([
              'data' => [
                'data' => [
                  'token',
                ],
              ],
            ])
            ->seeJson([
              'success' => true,
            ]);
      }

     /**
      * Test authenticate with invalid user email.
      */
       public function testInvalidEmailAuthentication()
       {
           $passwordRaw = str_random(10);
           $user = factory(App\User::class)->create([
             'password' => bcrypt($passwordRaw),
           ]);
           $payload = [
             'email' => $user->email.'bademail',
             'password' => $passwordRaw,
           ];

           $this->json('POST', $this->authUrl, $payload)
             ->seeJson([
               'success' => false,
               'code' => ErrorCode::NOT_AUTHORIZED
             ]);
       }

      /**
       * Test authenticate with invalid user password.
       */
        public function testInvalidPasswordAuthentication()
        {
            $passwordRaw = str_random(10);
            $user = factory(App\User::class)->create([
              'password' => bcrypt($passwordRaw),
            ]);
            $payload = [
              'email' => $user->email,
              'password' => $passwordRaw.'badpassword',
            ];

            $this->json('POST', $this->authUrl, $payload)
              ->seeJson([
                'success' => false,
                'code' => ErrorCode::NOT_AUTHORIZED
              ]);
        }
}
