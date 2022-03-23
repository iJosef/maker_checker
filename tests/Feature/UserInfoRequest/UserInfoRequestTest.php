<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserInfoRequestTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_to_fetch_all_pending_request()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $response = $this->get('/api/fetch_all_pending_requests');

        $response->assertStatus(200);
        $response->assertOk();
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_to_create_new_user_info_for_approval()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $response = $this->post('/api/create_new_user_info', [
            'first_name'          => "Mary",
            'last_name'           => "David",
            'email'               => "mary.david@gmail.com",
            'request_type'          =>  "1",
        ]);

        $response->assertStatus(201);
    }



     /**
     * A basic test example.
     *
     * @return void
     */
    public function test_to_update_user_info_for_approval()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $response = $this->post('/api/update_user_info', [
            'first_name'          => "Mary",
            'last_name'           => "David",
            'email'               => "mary.david@gmail.com",
            'request_type'          =>  "1",
            'user_info_id'          =>  "2"
        ]);

        $response->assertStatus(201);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_to_delete_user_info_for_approval()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $response = $this->get('/api/delete_user_info/1/3');

        $response->assertStatus(201);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_to_approve_a_create_request()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $response = $this->put('/api/approve_request/18/1');

        $response->assertStatus(201);
    }


     /**
     * A basic test example.
     *
     * @return void
     */
    public function test_to_approve_an_update_request()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $response = $this->put('/api/approve_request/7/2');

        $response->assertStatus(200);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_to_approve_an_delete_request()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $response = $this->put('/api/approve_request/3/3');

        $response->assertStatus(200);
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_to_decline_a_request()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $response = $this->delete('/api/decline_request/8');

        $response->assertStatus(200);
    }

}
