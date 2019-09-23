<?php

namespace Tests\Feature;

use App\Annonce;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnnonceTest extends TestCase
{
    use RefreshDatabase;

    public function data() {
        return [
            'content' => 'Je suis une action',
        ];
    }

    public function testCreateANewAnnonceWithValidDataAndActiveUserSucceed() {
        $user = factory(User::class)->state('active')->create();
        Passport::actingAs($user);
        $response = $this->json('POST', "/api/annonces", $this->data());
        $response->assertStatus(200);
        $this->assertCount(1, Annonce::all());
        $this->assertEquals($user->id, Annonce::first()->user_id);
    }

    public function testCreateAnAnnonceWithInvalidContentAndActiveUserFails() {
        $user = factory(User::class)->state('active')->create();
        Passport::actingAs($user);
        $response = $this->json("POST", "/api/annonces", array_merge($this->data(), ['content' => '']));
        $response->assertStatus(422); // Unprocessable Entity
        $this->assertCount(0, Annonce::all());
    }

    public function testAnnonceCannotBeCreatedByGuest() {
        $user = factory(User::class)->create();
        $response = $this->json("POST", "/api/annonces", $this->data());
        $response->assertStatus(401);
        $this->assertCount(0, Annonce::all());
    }

    public function testAnAnnonceCanBeUpdatedByActiveUser() {


        $user = factory(User::class)->state('active')->create();
        $annonce = factory(Annonce::class)->create(['user_id' => $user->id]);

        Passport::actingAs($annonce->author);
        $response = $this->json("PUT", '/api/annonce/'.$annonce->id, array_merge($this->data(), ['content' => 'Nouveau contenu']));

        $response->assertStatus(200);
        $this->assertEquals('Nouveau contenu', $annonce->fresh()->content);
    }

    public function testCannotUpdateAnAnnonceAsAGuest() {

        $user = factory(User::class)->create();
        $annonce = factory(Annonce::class)->create(['user_id' => $user->id]);

        $response = $this->put('/annonce/'.$annonce->id, array_merge($this->data(), ['content' => 'Nouveau contenu']));

        $response->assertRedirect();
    }

    public function testAnUserCantUpdateOtherUserAnnonce() {

        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $annonce = factory(Annonce::class)->create(array_merge(['user_id' => $firstUser->id], $this->data()));

        $response = $this->actingAs($secondUser)->put('/annonce/'.$annonce->id, array_merge($this->data(), ['content' => 'Nouveau contenu']));

        $response->assertStatus(403);
        $this->assertEquals('Je suis une action', $annonce->fresh()->content);
    }

    public function testAnAdminCanUpdateAnyAnnonce() {
        $admin = factory(User::class)->state('admin')->create();
        $user = factory(User::class)->create();
        $annonce = factory(Annonce::class)->create(['user_id' => $user->id]);

        $response = $this->actingAs($admin)->put('/annonce/'.$annonce->id, ['content' => 'Nouveau contenu']);

        $response->assertStatus(200);
        $this->assertEquals('Nouveau contenu', $annonce->fresh()->content);
    }

    public function testAnActiveUserCanDeleteOneOfHisAnnonce() {

        $user = factory(User::class)->state('active')->create();
        $annonce = factory(Annonce::class)->create(['user_id' => $user->id]);

        $this->assertCount(1, Annonce::all());

        $response = $this->actingAs($user)->delete('/annonce/'.$annonce->id);

        $response->assertStatus(200);
        $this->assertCount(0, Annonce::all());
    }

    public function testAnAdminCanDeleteAnyAnnonce() {
        $admin = factory(User::class)->state('admin')->create();
        $user = factory(User::class)->create();
        $annonce = factory(Annonce::class)->create(['user_id' => $user->id]);
        $this->assertCount(1, Annonce::all());

        $response = $this->actingAs($admin)->delete('/annonce/'.$annonce->id, ['content' => 'Nouveau contenu']);

        $response->assertStatus(200);
        $this->assertCount(0, Annonce::all());
    }

    public function testAnUserCantDeleteOtherUserAnnonce() {

        $firstUser = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $annonce = factory(Annonce::class)->create(array_merge(['user_id' => $firstUser->id], $this->data()));
        $this->assertCount(1, Annonce::all());

        $response = $this->actingAs($secondUser)->delete('/annonce/'.$annonce->id, array_merge($this->data(), ['content' => 'Nouveau contenu']));

        $response->assertStatus(403);
        $this->assertCount(1, Annonce::all());
    }

    public function testAnInactiveUserCannotPostAnnonce() {
        $user = factory(User::class)->create();
        $this->assertEquals(0, $user->active);
        $response = $this->actingAs($user)->post("/annonces", $this->data());
        $response->assertStatus(403);

    }

//    public function testAnInactiveUserCannotDeleteAnnonce() {
//
//    }

}
