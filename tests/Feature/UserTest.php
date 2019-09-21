<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function data()
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.org',
            'password' => 'password',
        ];
    }

    public function testOnRegisterAnUserIsAddedAndInactive()
    {
        $this->post('/register', array_merge($this->data(), ["password_confirmation" => 'password']));
        $user = User::first();
        $this->assertCount(1, User::all());
        $this->assertEquals(0, $user->role);
        $this->assertEquals(0, $user->active);
    }

    public function testInActiveUserCannotBeLoggedIn()
    {

        $user = factory(User::class)->create();
        $this->assertEquals(0, $user->active);
        $this->assertCount(1, User::all());


        $response = $this->post('/login', ["email" => $user->email, 'password' => 'password']);
        $this->assertInstanceOf(ValidationException::class, $response->exception);
        $response->assertRedirect();
    }

    public function testActiveUserCanBeLoggedIn() {
        $user = factory(User::class)->create(['active' => 1]);
        $this->assertEquals(1, $user->active);
        $this->assertCount(1, User::all());


        $response = $this->post('/login', ["email" => $user->email, 'password' => 'password']);
        $response->assertRedirect("/home");
    }

    public function testUpdateNameLastNameAndAvatarSucceedWhenUserIsConnected()
    {
        $user = factory(User::class)->create();
        $file = UploadedFile::fake()->image('avatar.jpg');
        $data = ['first_name' => 'Jane', 'last_name' => 'Yoa', 'avatar' => $file];

        $response = $this->actingAs($user)->patch('/users/' . $user->id, $data);


        $this->assertEquals('Jane', $user->fresh()->first_name);
        $this->assertEquals('Yoa', $user->fresh()->last_name);
        $this->assertNotNull($user->fresh()->avatar);
        Storage::disk('local')->assertExists('public/avatars/' . $file->hashName());
    }

    public function testUpdateNameLastNameAndAvatarFailWhenUserIsNotConnected()
    {
        $user = factory(User::class)->create();

        $file = UploadedFile::fake()->image('avatar.jpg');
        $data = ['first_name' => 'Jane', 'last_name' => 'Yoa', 'avatar' => $file];

        $response = $this->patch('/users/' . $user->id, $data);
        $response->assertRedirect();
    }

    public function testUserCannotUpdateOtherUserInformations()
    {

        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();

        $file = UploadedFile::fake()->image('avatar.jpg');
        $data = ['first_name' => 'Jane', 'last_name' => 'Yoa', 'avatar' => $file];

        $response = $this->actingAs($firstUser)->patch('/users/' . $secondUser->id, $data);
        $response->assertStatus(403);

    }

    public function testChangePasswordByUserWorks()
    {

        $user = factory(User::class)->create();

        $data = ['old_password' => 'password', 'new_password' => 'new_password', 'new_password_confirmation' => 'new_password'];

        $response = $this->actingAs($user)->patch('/users/' . $user->id . '/password', $data);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('new_password', $user->fresh()->password));
    }

    public function testChangePasswordByUserFailsWhenTheOldPasswordIsIncorrect()
    {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $data = ['old_password' => 'old_password', 'new_password' => 'new_password', 'new_password_confirmation' => 'new_password'];

        $response = $this->actingAs($user)->patch('/users/' . $user->id . '/password', $data);

        $response->assertRedirect();
        $this->assertFalse(Hash::check('new_password', $user->fresh()->password));
    }

    public function testTryingToChangePasswordWithThisRouteWhileNotBeingConnectedFails()
    {
        $user = factory(User::class)->create();
        $data = ['old_password' => 'old_password', 'new_password' => 'new_password', 'new_password_confirmation' => 'new_password'];
        $response = $this->patch('/users/' . $user->id . '/password', $data);
        $response->assertRedirect();
    }

    public function testUserCannotChangeOtherUserPassword()
    {
        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $data = ['old_password' => 'old_password', 'new_password' => 'new_password', 'new_password_confirmation' => 'new_password'];

        $response = $this->actingAs($firstUser)->patch('/users/' . $secondUser->id . '/password', $data);

        $response->assertStatus(403);
    }

    public function testCreateAUserWithFileSucceed() {
        $file = UploadedFile::fake()->image('avatar.jpg');
        $data = array_merge($this->data(), ['avatar' => $file, 'password_confirmation' => 'password']);

        $response = $this->post('/register', $data);


        $this->assertCount(1, User::all());
        Storage::disk('local')->assertExists('public/avatars/' . $file->hashName());
    }

    public function testUserCanAccessHisEditPageWhenHesLoggedIn() {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/users/'.$user->id.'/edit');
        $response->assertStatus(200);
    }
}
