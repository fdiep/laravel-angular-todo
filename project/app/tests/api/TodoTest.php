<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;
use App\Http\Controllers\ErrorCode as ErrorCode;

class TodoTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Auth Urls.
     */
    private $todoUrl;

     /**
      * Build all urls.
      */
     public function __construct()
     {
         $this->todoUrl = $this->apiUrl.'/todo';
     }

      /**
       * Create Todo.
       */
      public function testValidCreate()
      {
          $loggedInData = $this->login();

          $todo = factory(App\Todo::class)->make();
          $payload = [
            'description' => $todo->description
          ];

          $this->json('POST', $loggedInData['url'], $payload)
            ->seeJson([
              'success' => true,
              'description' => $todo->description,
              'owner_id' => $loggedInData['user']->id,
            ])
            ->seeJsonStructure([
              'data' => [
                'data' => [
                  'description','owner_id','updated_at','created_at','id'
                ],
              ],
            ]);
      }

     /**
      * Get Valid Todo.
      */
     public function testValidGet()
     {
         $loggedInData = $this->login();

         $todo = factory(App\Todo::class)->create([
           'owner_id' => $loggedInData['user']->id
         ]);

         $url = $this->createGetUrl($todo->id, $loggedInData['token']);
         $this->json('GET', $url)
           ->seeJson([
             'success' => true,
             'description' => $todo->description,
             'owner_id' => $loggedInData['user']->id,
           ])
           ->seeJsonStructure([
             'data' => [
               'data' => [
                 'description','owner_id','updated_at','created_at','id'
               ],
             ],
           ]);
     }

      /**
       * Get Invalid Todo.
       */
      public function testInvalidGet()
      {
          $loggedInDataMe = $this->login();
          $loggedInDataOther = $this->login();

          // create todo for other
          $todo = factory(App\Todo::class)->create([
            'owner_id' => $loggedInDataOther['user']->id
          ]);

          // attempt to get other's todo as me
          $url = $this->createGetUrl($todo->id, $loggedInDataMe['token']);
          $this->json('GET', $url)
            ->assertResponseStatus(ErrorCode::FORBIDDEN)
            ->seeJson([
              'success' => false,
              'code' => ErrorCode::FORBIDDEN,
            ]);
      }

      /**
      * Get Todo List Empty.
      */
      public function testListEmpty()
      {
        $this->callList(0);
      }

      /**
      * Get Todo List Single.
      */
      public function testListSingle()
      {
        $this->callList(1);
      }

      /**
       * Get Todo List Many.
       */
      public function testListMany()
      {
        $this->callList(20);
      }

       /**
        * Get Todo List.
        */
       private function callList($todoCount)
       {
           $loggedInData = $this->login();
           // create todos in DB
           if($todoCount > 0){
             factory(App\Todo::class, $todoCount)->create([
               'owner_id' => $loggedInData['user']->id
             ]);
           }
           // test json object
           $apiCall = $this->json('GET', $loggedInData['url']);
           $apiCall->seeJson([
             'success' => true,
           ]);
           // make sure all list items have same structure
           if($todoCount > 0){
             $apiCall->seeJsonStructure([
                 'data' => [
                   'data' => [
                     '*' => [
                       'description','owner_id','updated_at','created_at','id'
                     ]
                   ],
                 ],
               ]);
           }
          // make sure we get all todos
          $content = $this->call('GET', $loggedInData['url'])->getContent();
          $jsonResponse = json_decode($content);
          $this->assertEquals($todoCount, count($jsonResponse->data->data));
       }

      /**
       * Create user and JWT token.
       * Return url and user
       */
      private function login()
      {
          $passwordRaw = str_random(10);
          $user = factory(App\User::class)->create([
            'password' => bcrypt($passwordRaw),
          ]);

          // create JWT token
          $credentials = ['email' => $user->email, 'password' => $passwordRaw];
          $token = JWTAuth::attempt($credentials);

          return [
            'url' => $this->todoUrl.'?token='.$token,
            'token' => $token,
            'user' => $user,
          ];
      }

       /**
        * Create user and JWT token.
        * Return url and user
        */
       private function createGetUrl($todoId, $token)
       {
         return $this->todoUrl.'/'.$todoId.'?token='.$token;
       }
}