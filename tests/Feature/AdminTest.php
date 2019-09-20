<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTest extends TestCase
{

    use RefreshDatabase;

    public function testAdminCanToggleOtherUsersActivation()
    {
        $admin = factory(User::class)->state('admin')->create();
        $user = factory(User::class)->create();
        Passport::actingAs($admin);
        $response = $this->json('PATCH', 'api/users/'.$user->id.'/activation', ['active' => true]);
        $response->assertStatus(200);
        $this->assertEquals(true, $user->fresh()->active);
    }

    public function testGuestCannotChangeOtherUsersActivation()  {
        $user = factory(User::class)->create();
        $response = $this->json('PATCH', 'api/users/'.$user->id.'/activation', ['active' => true]);
        $response->assertStatus(401);
    }

    public function testNonAdminUserCannotChangeOtherUsersActivation() {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $response = $this->json('PATCH', 'api/users/'.$user->id.'/activation', ['active' => true]);
        $response->assertStatus(403);
    }

    public function testAdminCanDeleteUsers() {

        $admin = factory(User::class)->state('admin')->create();
        $user = factory(User::class)->create();
        Passport::actingAs($admin);
        $response = $this->json('DELETE', 'api/users/'.$user->id);
        $response->assertStatus(200);
        $this->assertCount(1, User::all());
    }

    public function testCommonUsersCannotDeleteEachOthers() {
        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        Passport::actingAs($firstUser);
        $response = $this->json('DELETE', 'api/users/'.$secondUser->id);
        $response->assertStatus(403);
        $this->assertCount(2, User::all());
    }

    public function testGuestCannotDeleteUser() {
        $user = factory(User::class)->create();
        $response = $this->json('DELETE', 'api/users/'.$user->id);

        $response->assertStatus(401);
        $this->assertCount(1, User::all());
    }

    public function testAdminGotRedirectedToDashboard() {
        $admin = factory(User::class)->state('admin')->create();

        $response = $this->post('login', ['email' => $admin->email, 'password' => 'password']);

        $response->assertRedirect("/dashboard");
    }

    public function testAdminCanGetUsers() {
        $admin = factory(User::class)->state('admin')->create();
        Passport::actingAs($admin);
        $response = $this->json('GET', '/api/users');
        $response->assertStatus(200);
    }
}
