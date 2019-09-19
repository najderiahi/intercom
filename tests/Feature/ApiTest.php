<?php

namespace Tests\Feature;

use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function testAConnectedUserCanBeAccesedViaApi()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->state('admin')->create();
        Passport::actingAs($user, []);
        $response = $this->get( '/api/user');
        $response->assertStatus(200);

    }
}
