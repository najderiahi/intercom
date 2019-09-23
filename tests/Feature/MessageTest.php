<?php

namespace Tests\Feature;

use App\Message;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function testAnActiveUserCanSendAMessageToAnotherOne()
    {
        $sender = factory(User::class)->state('active')->create();
        $receiver = factory(User::class)->create();

        Passport::actingAs($sender);
        $response = $this->json('POST', 'api/conversations/' . $receiver->id, ['content' => "Je suis un message"]);
        $message = Message::first();
        $response->assertStatus(200);
        $this->assertCount(1, Message::all());
        $this->assertNotNull($message);
        $this->assertEquals($receiver->id, $message->receiver_id);
        $this->assertEquals($sender->id, $message->sender_id);
    }

    public function testAnInactiveUserCantSendAMessageToAUser()
    {
        $sender = factory(User::class)->create();
        $receiver = factory(User::class)->create();

        Passport::actingAs($sender, []);
        $response = $this->json('POST', 'api/conversations/' . $receiver->id, ['content' => "Je suis un message"]);

        $message = Message::first();
        $response->assertStatus(403);
        $this->assertCount(0, Message::all());
        $this->assertNull($message);
    }

    public function testAGuestCantSendAMessageToAUser()
    {
        $receiver = factory(User::class)->create();

        $response = $this->json('POST', '/api/conversations/' . $receiver->id, ['content' => "Je suis un message"]);

        $message = Message::first();
        $response->assertStatus(401);
        $this->assertCount(0, Message::all());
        $this->assertNull($message);
    }



}
