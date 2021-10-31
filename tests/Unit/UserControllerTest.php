<?php

// namespace Tests\Feature\User;

// use Tests\TestCase;
// use App\Models\Game;
// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
// use App\Http\Controllers\UserController;
// use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Foundation\Testing\RefreshDatabase;

// class UserControllerTest extends TestCase
// {
//     use RefreshDatabase, WithFaker;

//     public function setUp(): void
//     {
//         parent::setUp();
//     }

//     /**
//      *
//      * @return void
//      */
//     public function testIndex(): void
//     {
//         // $request = Request::create('/api/users', 'GET',[
//         //     'title'     =>  'foo',
//         //     'text'  =>  'bar',
//         // ]);
//         $request = Request::create('/api/users', 'GET');

//         $controller = new UserController();
//         $response = $controller->index($request);

//         info(["RESPONSE" => $response]);
//         $this->assertEquals(200, $response->status());
//     }
// }