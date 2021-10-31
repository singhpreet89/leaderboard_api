<?php

namespace Tests\Feature\User;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
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
    public function testIndex(): void
    {
        $numberOfUsers = 5;
        User::factory()->count($numberOfUsers)->hasGame(1)->create();

        $response = $this->getJson(route('users.index'));

        $response->assertStatus(200);
        $response->assertJsonCount($numberOfUsers, $key = "data");
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "name",
                    "email",
                    "age",
                    "points",
                    "address" => [
                        "line1",
                        "line2",
                        "city",
                        "province",
                        "country",
                        "postal_code",
                    ],
                ]
            ],
            "links" => [
                "first",
                "last",
                "prev",
                "next"
            ],
            "meta" => [
                "current_page",
                "from",
                "last_page",
                "links",
                "path",
                "per_page",
                "to",
                "total"
            ]
        ]);
    }

    /**
     *
     * @return void
     */
    public function testStore(): void
    {
        $birthDate  = "01/20/1989";
        $age        = Carbon::parse($birthDate)->age;
        $country    = "US";

        $payload = [
            "name"          => $this->faker->name,
            "email"         => $this->faker->safeEmail,
            "birth_date"    => $birthDate,
            "line1"         => $this->faker->streetAddress,
            "line2"         => $this->faker->secondaryAddress,
            "city"          => $this->faker->city,
            "province"      => $this->faker->state,
            "country"       => $country,
            "postal_code"   => $this->faker->postcode,
        ];

        $response = $this->postJson(route('users.store'), $payload);

        $this->assertDatabaseHas('users', [
            "name"          => $payload["name"],
            "email"         => $payload["email"],
            "age"           => $age,
            "line1"         => $payload["line1"],
            "line2"         => $payload["line2"],
            "city"          => $payload["city"],
            "province"      => $payload["province"],
            "country"       => $country,
            "postal_code"   => $payload["postal_code"],
            "deleted_at"    => null,
        ]);
        $this->assertDatabaseHas('games', [
            "points" => 0,
            "deleted_at" => null,
        ]);
        $response->assertCreated();
        $response->assertJson([
            "data" => [
                "id"        => 1,
                "name"      => $payload["name"],
                "email"     => $payload["email"],
                "age"       => $age,
                "points"    => 0,
                "address"   => [
                    "line1"         => $payload["line1"],
                    "line2"         => $payload["line2"],
                    "city"          => $payload["city"],
                    "province"      => $payload["province"],
                    "country"       => $country,
                    "postal_code"   => $payload["postal_code"],
                ]
            ]
        ]);
    }

    /**
     *
     * @return void
     */
    public function testShow(): void
    {
        User::factory()->count(1)->hasGame(1)->create();
    
        $user = User::first();
        $game = Game::first();

        $response = $this->getJson(route('users.show', $user->id));

        $response->assertOk();
        $response->assertJson([
            "data" => [
                "id"        => $user->id, 
                "name"      => $user->name,
                "email"     => $user->email,
                "age"       => $user->age,
                "points"    => $game->points,
                "address"   => [
                    "line1"         => $user->line1,
                    "line2"         => $user->line2,
                    "city"          => $user->city,
                    "province"      => $user->province,
                    "country"       => $user->country,
                    "postal_code"   => $user->postal_code,
                ]
            ]
        ]);
    }

    /**
     *
     * @return void
     */
    public function testUpdateAddition(): void
    {
        $numberOfUsers = 5;
        User::factory()->count($numberOfUsers)->hasGame(1)->create();
    
        $user = User::first();
        $game = Game::first();

        $payload = [
            "operation" => "addition",
        ];

        $response = $this->patchJson(route('users.update', $user->id), $payload);

        $response->assertStatus(200);
        $response->assertJsonCount($numberOfUsers, $key = "data");
        $this->assertDatabaseHas('games', [
            "points" => ($game->points + 1),
        ]);
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "name",
                    "email",
                    "age",
                    "points",
                    "address" => [
                        "line1",
                        "line2",
                        "city",
                        "province",
                        "country",
                        "postal_code",
                    ],
                ]
            ],
            "links" => [
                "first",
                "last",
                "prev",
                "next"
            ],
            "meta" => [
                "current_page",
                "from",
                "last_page",
                "links",
                "path",
                "per_page",
                "to",
                "total"
            ]
        ]);
    }

    /**
     *
     * @return void
     */
    public function testUpdateSubtraction(): void
    {
        $numberOfUsers = 5;
        User::factory()->count($numberOfUsers)->hasGame(1)->create();
    
        $user = User::first();
        $game = Game::first();

        $payload = [
            "operation" => "subtraction",
        ];

        $response = $this->patchJson(route('users.update', $user->id), $payload);

        $response->assertStatus(200);
        $response->assertJsonCount($numberOfUsers, $key = "data");
        $this->assertDatabaseHas('games', [
            "points" => ($game->points - 1),
        ]);
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id",
                    "name",
                    "email",
                    "age",
                    "points",
                    "address" => [
                        "line1",
                        "line2",
                        "city",
                        "province",
                        "country",
                        "postal_code",
                    ],
                ]
            ],
            "links" => [
                "first",
                "last",
                "prev",
                "next"
            ],
            "meta" => [
                "current_page",
                "from",
                "last_page",
                "links",
                "path",
                "per_page",
                "to",
                "total"
            ]
        ]);
    }

    /**
     *
     * @return void
     */
    public function testDestroy(): void
    {
        User::factory()->count(1)->hasGame(1)->create();
    
        $user = User::first();
        $game = Game::first();

        $response = $this->deleteJson(route('users.destroy', $user->id));

        $response->assertOk();
        $this->assertSoftDeleted($user);
        $this->assertSoftDeleted($game);
        $response->assertJson([
            "data" => [
                "id"        => $user->id, 
                "name"      => $user->name,
                "email"     => $user->email,
                "age"       => $user->age,
                "points"    => $game->points,
                "address"   => [
                    "line1"         => $user->line1,
                    "line2"         => $user->line2,
                    "city"          => $user->city,
                    "province"      => $user->province,
                    "country"       => $user->country,
                    "postal_code"   => $user->postal_code,
                ]
            ]
        ]);
    }
}