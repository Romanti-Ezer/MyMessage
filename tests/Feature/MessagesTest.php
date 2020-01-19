<?php

namespace Tests\Feature;

use App\Message;
use App\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessagesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function list_of_messages_can_be_fetched_for_the_authenticated_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $message = factory(Message::class)->create( ['user_id' => $user->id] );
        $anotherMessage = factory(Message::class)->create( ['user_id' => $anotherUser->id] );

        $response = $this->get( '/api/messages?api_token=' . $user->api_token );

        $response->assertJsonCount(1)
            ->assertJson( [
                'data' => [
                    [
                        'data' => [
                            'message_id' => $message->id
                        ]
                    ]
                ]
            ] );
    }

    /** @test */
    public function unauthenticated_user_should_be_redirected()
    {
        $response = $this->post( '/api/messages', array_merge($this->data(), ['api_token' => '']) );

        $response->assertRedirect( '/login' );

        $this->assertCount(0, Message::all());
    }

    /** @test */
    public function authenticated_user_can_add_a_message()
    {
        $this->withoutExceptionHandling();

        $response = $this->post( '/api/messages', $this->data() );

        $message = Message::first();

        $this->assertEquals( 'New product line released!', $message->subject );
        $this->assertEquals( 'Hey customer, we would like to let you know that we have a new line of products being released!', $message->content );
        $this->assertEquals( '01/20/2020', $message->startDate->format('m/d/Y') );
        $this->assertEquals( '01/25/2020', $message->expirationDate->format('m/d/Y') );

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'message_id' => $message->id
                ],
                'links' => [
                    'self' => $message->path(),
                ]
            ]);
    }

    /** @test */
    public function fields_are_required()
    {
        collect( ['subject', 'content', 'startDate', 'expirationDate'])
            ->each(function($field) {
                $response = $this->post( '/api/messages', array_merge($this->data(), [ $field => '']));
        
                $response->assertSessionHasErrors( $field );
                $this->assertCount(0, Message::all());
            });
    }

    /** @test */
    public function dates_are_properly_stored()
    {
        collect( ['startDate', 'expirationDate'])
            ->each(function($field, $index) {
                $response = $this->post( '/api/messages', array_merge($this->data()) );

                $this->assertCount($index + 1, Message::all());
                $this->assertInstanceOf( Carbon::class, Message::first()->$field );
                $this->assertEquals( $this->data()[$field], Message::first()->$field->format('m/d/Y'));
            });
    }

    /** @test */
    public function message_can_be_retrieved()
    {
        $this->withoutExceptionHandling();
        
        $message = factory(Message::class)->create( ['user_id' => $this->user->id] );
        
        $response = $this->get( '/api/messages/' . $message->id . '?api_token=' . $this->user->api_token );

        $response->assertJson([
            'data' => [
                'message_id' => $message->id,
                'subject' => $message->subject,
                'content' => $message->content,
                'startDate' => $message->startDate->format('m/d/Y'),
                'expirationDate' => $message->expirationDate->format('m/d/Y'),
                'last_updated' => $message->updated_at->diffForHumans(),
            ]
        ]);
    }

    /** @test */
    public function only_the_users_messages_can_be_retrieved()
    {
        $message = factory(Message::class)->create( ['user_id' => $this->user->id] );
        
        $anotherUser = factory(User::class)->create();

        $response = $this->get( '/api/messages/' . $message->id . '?api_token=' . $anotherUser->api_token );

        $response->assertStatus(403);
    }

    /** @test */
    public function message_can_be_patched()
    {
        $message = factory(Message::class)->create( ['user_id' => $this->user->id] );

        $response = $this->patch( '/api/messages/' . $message->id, $this->data() );

        $message->refresh();

        $this->assertEquals( 'New product line released!', $message->subject );
        $this->assertEquals( 'Hey customer, we would like to let you know that we have a new line of products being released!', $message->content );
        $this->assertEquals( '01/20/2020', $message->startDate->format('m/d/Y') );
        $this->assertEquals( '01/25/2020', $message->expirationDate->format('m/d/Y') );
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'message_id' => $message->id
            ],
            'links' => [
                'self' => $message->path(),
            ]
        ]);
    }

    /** @test */
    public function only_the_owner_of_the_message_can_patch_the_message()
    {
        $message = factory(Message::class)->create();

        $anotherUser = factory(User::class)->create();

        $response = $this->patch( '/api/messages/' . $message->id, array_merge($this->data(), ['api_token' => $anotherUser->api_token]) );

        $response->assertStatus(403);
    }

    /** @test */
    public function message_can_be_deleted()
    {
        $message = factory(Message::class)->create( ['user_id' => $this->user->id] );

        $response = $this->delete( '/api/messages/' . $message->id, [ 'api_token' => $this->user->api_token ] );

        $this->assertCount(0, Message::all());
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function only_the_owner_can_delete_the_message()
    {
        $message = factory(Message::class)->create();

        $anotherUser = factory(User::class)->create();

        $response = $this->delete( '/api/messages/' . $message->id, [ 'api_token' => $anotherUser->api_token ] );

        $response->assertStatus(403);
    }

    private function data() {
        return [
            'subject' => 'New product line released!',
            'content' => 'Hey customer, we would like to let you know that we have a new line of products being released!',
            'startDate' => '01/20/2020',
            'expirationDate' => '01/25/2020',
            'api_token' => $this->user->api_token,
        ];
    }
}
