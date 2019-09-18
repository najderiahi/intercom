<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Auth;
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
        $response = $this->actingAs($admin)->patch('/users/'.$user->id.'/activation', ['active' => true]);
        $response->assertStatus(200);
        $this->assertEquals(true, $user->fresh()->active);
    }

    public function testGuestCannotChangeOtherUsersActivation()  {
        $user = factory(User::class)->create();
        $response = $this->patch('/users/'.$user->id.'/activation', ['active' => true]);
        $response->assertRedirect();
    }

    public function testNonAdminUserCannotChangeOtherUsersActivation() {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->patch('/users/'.$user->id.'/activation', ['active' => true]);
        $response->assertStatus(403);
    }

    public function testAdminCanDeleteUsers() {

        $this->withoutExceptionHandling();

        $admin = factory(User::class)->state('admin')->create();
        $user = factory(User::class)->create();
        $response = $this->actingAs($admin)->delete('/users/'.$user->id);
        $response->assertStatus(200);
        $this->assertCount(1, User::all());
    }

    public function testCommonUsersCannotDeleteEachOthers() {
        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $response = $this->actingAs($firstUser)->delete('/users/'.$secondUser->id);
        $response->assertStatus(403);
        $this->assertCount(2, User::all());
    }

    public function testGuestCannotDeleteUser() {
        $user = factory(User::class)->create();
        $response = $this->delete('/users/'.$user->id);

        $response->assertRedirect();
        $this->assertCount(1, User::all());
    }
}
