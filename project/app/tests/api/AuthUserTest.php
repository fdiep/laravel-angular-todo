<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use App\Http\Controllers\ErrorCode as ErrorCode;

class AuthUserTest extends TestCase
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
     * Test with valid token.
     */
      public function testValidTokenGetUser()
      {
          $passwordRaw = str_random(10);
          $user = factory(App\User::class)->create([
            'password' => bcrypt($passwordRaw),
          ]);

          // create JWT token
          $credentials = ['email' => $user->email, 'password' => $passwordRaw];
          $token = JWTAuth::attempt($credentials);

          $url = $this->userUrl . '?token=' . $token;

          $this->json('GET', $url)
            ->seeJson([
              'success' => true,
              'name' => $user->name,
              'email' => $user->email,
            ]);
      }

      /**
       * Test with invalid token.
       */
       public function testInvalidTokenGetUser()
       {
           $token = 'badtoken';
           $url = $this->userUrl . '?token=' . $token;

           $this->json('GET', $url)
             ->seeJson([
               'success' => false,
               'code' => ErrorCode::FORBIDDEN,
             ]);
       }
}
