<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_route_exists(): void
    {
        $response = $this->get('/welcome');

        $response->assertStatus(200);
    }

    public function test_home_route_exists(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login_route_exists(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_register_route_exists(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_dashboard_route_requires_authentication(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_logout_route_requires_authentication(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_route_requires_authentication(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }

    public function test_startpage_route_requires_authentication(): void
    {
        $response = $this->get('/startpage');

        $response->assertRedirect(route('login'));
    }

    public function test_testpage_route_is_public(): void
    {
        $response = $this->get('/testpage');

        $response->assertStatus(200);
    }

    public function test_testform_route_is_public(): void
    {
        $response = $this->get('/testform');

        $response->assertStatus(200);
    }

    public function test_testing_route_is_public(): void
    {
        $response = $this->get('/testing');

        $response->assertStatus(200);
    }

    public function test_guestpage_route_is_public(): void
    {
        $response = $this->get('/guestpage');

        $response->assertStatus(200);
    }

    public function test_greeting_from_bootstrap_route_works(): void
    {
        $response = $this->get('/greeting_from_bootstrap');

        $response->assertStatus(200);
        $response->assertSee('Gruß vom Package Bootstrap');
    }

    public function test_all_named_routes_exist(): void
    {
        $this->assertTrue(route('welcome') !== null);
        $this->assertTrue(route('login') !== null);
        $this->assertTrue(route('register') !== null);
        $this->assertTrue(route('dashboard') !== null);
        $this->assertTrue(route('logout') !== null);
        $this->assertTrue(route('admin') !== null);
        $this->assertTrue(route('startpage') !== null);
        $this->assertTrue(route('guestpage') !== null);
        $this->assertTrue(route('testpage') !== null);
        $this->assertTrue(route('testform') !== null);
        $this->assertTrue(route('testing') !== null);
    }
}
