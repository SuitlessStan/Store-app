<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    /** @test */
    public function it_can_list_all_customers()
    {
        Customer::factory()->count(5)->create();

        $response = $this->actingAsUser()->get('/api/customers');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    /** @test */
    public function it_can_create_a_customer()
    {
        $response = $this->actingAsUser()->post('/api/customers', [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $response->assertStatus(201)
            ->assertJson(['name' => 'John Doe']);
    }

    /** @test */
    public function it_can_show_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsUser()->get("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson(['id' => $customer->id]);
    }

    /** @test */
    public function it_can_update_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsUser()->put("/api/customers/{$customer->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
            ->assertJson(['name' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->actingAsUser()->delete("/api/customers/{$customer->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    private function actingAsUser()
    {
        $user = \App\Models\User::factory()->create();
        return $this->actingAs($user, 'sanctum');
    }
}
