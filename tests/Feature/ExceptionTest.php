<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExceptionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     *
     * @return void
     */
    public function testWrongUrlException(): void
    {
        $appUrl = env('APP_URL'); // http://localhost:6000
        $url    = $appUrl . "/api/wrongUrl";
        $responseMessage = [
            "message" => "Not Found.",
            "errors" => [
                "url" => [
                   "$url is invalid."
                ]
            ]
        ];

        $response = $this->get($url);

        $response->assertNotFound();
        $response->assertExactJson($responseMessage);
    }

     /**
     *
     * @return void
     */
    public function testMethodNotAllowedException(): void
    {
        $appUrl = env('APP_URL'); // http://localhost:6000
        $url    = $appUrl . "/api/users";
        $responseMessage = [
            "message" => "Method not allowed.",
            "errors" => [
                "url" => [
                   "The PATCH method is not supported for $url"
                ]
            ]
        ];

        $response = $this->patch($url);
    
        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
        $response->assertExactJson($responseMessage);
    }

    /**
     *
     * @return void
     */
    public function testNonExistingUserException(): void
    {
        $appUrl = env('APP_URL'); // http://localhost:6000
        $url    = $appUrl . "/api/users/1000000000";
        $responseMessage = [
            "message" => "Not Found.",
            "errors" => [
                "url" => [
                   "$url is invalid."
                ]
            ]
        ];

        $response = $this->get($url);

        $response->assertNotFound();
        $response->assertExactJson($responseMessage);
    }

    /**
     *
     * @return void
     */
    public function testUserCreationValidationErrors(): void
    {
        $payload = [
            "name"          => $this->faker->name,
            // "email"         => $this->faker->safeEmail,
            // "birth_date"    => $birthDate,
            "line1"         => $this->faker->streetAddress,
            "line2"         => $this->faker->secondaryAddress,
            "city"          => $this->faker->city,
            "province"      => $this->faker->state,
            // "country"       => "US",
            // "postal_code"   => $this->faker->postcode,
        ];
    
        $response = $this->postJson(route('users.store'), $payload);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            "email",
            "birth_date",
            "country",
            "postal_code"
        ]);
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "email" => [
                    "The email field is required."
                ],
                "birth_date" => [
                    "The birth date field is required."
                ],
                "country" => [
                    "The country field is required."
                ],
                "postal_code" => [
                    "The postal code field is required."
                ]
            ]
        ]);
    }

    /**
     *
     * @return void
     */
    public function testUpdateUserScoreError(): void
    {
        $numberOfUsers = 5;
        User::factory()->count($numberOfUsers)->hasGame(1)->create();
    
        $user = User::first(); 
        Game::where("user_id", $user->id)->update(["points" => 0]);

        $payload = [
            "operation" => "subtraction",
        ];

        $response = $this->patchJson(route("users.update", $user->id), $payload);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            "operation",
        ]);
        $response->assertJson([
            "message" => "The given data was invalid.",
            "errors" => [
                "operation" => [
                    "The subtraction operation is not permitted with 0 points."
                ],
            ]
        ]);
    }
}