<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_middleware_protects_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_auth_middleware_protects_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_auth_middleware_protects_startpage(): void
    {
        $response = $this->get('/startpage');

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_middleware_redirects_authenticated_users_from_login(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_guest_middleware_redirects_authenticated_users_from_register(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('register'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_authenticated_users_can_access_protected_routes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertStatus(200);

        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(200);

        $this->actingAs($user)
            ->get('/startpage')
            ->assertStatus(200);
    }

    public function test_public_routes_are_accessible_without_authentication(): void
    {
        $this->get('/welcome')->assertStatus(200);
        $this->get('/testpage')->assertStatus(200);
        $this->get('/testform')->assertStatus(200);
        $this->get('/testing')->assertStatus(200);
    }

    public function test_guestpage_is_accessible_without_authentication(): void
    {
        $response = $this->get('/guestpage');

        $response->assertStatus(200);
    }
}
