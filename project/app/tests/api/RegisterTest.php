<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\ErrorCode as ErrorCode;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

  /**
   * Auth Urls.
   */
  private $registerUrl;

  /**
   * Build all urls.
   */
  public function __construct()
  {
      $this->registerUrl = $this->apiUrl.'/register';
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
        'password' => $user->password,
      ];

      $this->json('POST', $this->registerUrl, $payload)
        ->seeJson([
          'name' => $user->name,
          'email' => $user->email,
        ]);
    }

  /**
   * Test registration with invalid user email.
   */
  public function testInvalidEmailRegistration()
  {
      $user = factory(App\User::class)->make();
      $payload = [
        'name' => $user->name,
        'email' => 'invalidEmail',
        'password' => $user->password,
      ];

      $this->json('POST', $this->registerUrl, $payload)
        ->seeJson([
          'success' => false,
          'code' => ErrorCode::NOT_AUTHORIZED,
        ]);
  }

  /**
   * Test registration with empty user password.
   */
  public function testEmptyPasswordRegistration()
  {
      $user = factory(App\User::class)->make();
      $payload = [
        'name' => $user->name,
        'email' => $user->email,
        'password' => '',
      ];

      $this->json('POST', $this->registerUrl, $payload)
        ->assertResponseStatus(ErrorCode::NOT_AUTHORIZED)
        ->seeJson([
          'success' => false,
          'code' => ErrorCode::NOT_AUTHORIZED,
        ]);
  }

  /**
   * Test registration with invalid user password.
   * Less than 6 characters.
   */
  public function testInvalidPasswordRegistration()
  {
      $user = factory(App\User::class)->make();
      $payload = [
        'name' => $user->name,
        'email' => $user->email,
        'password' => '12345', // less than 6 characters
      ];

      $this->json('POST', $this->registerUrl, $payload)
        ->assertResponseStatus(ErrorCode::NOT_AUTHORIZED)
        ->seeJson([
          'success' => false,
          'code' => ErrorCode::NOT_AUTHORIZED,
        ]);
  }

  /**
   * Test registration with invalid user password.
   */
  public function testDuplicateRegistration()
  {
      $user = factory(App\User::class)->create(); // this user is created and saved in DB
      $payload = [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password,
      ];

      $this->json('POST', $this->registerUrl, $payload)
        ->assertResponseStatus(ErrorCode::NOT_AUTHORIZED)
        ->seeJson([
          'success' => false,
          'code' => ErrorCode::NOT_AUTHORIZED,
        ]);
  }
}
