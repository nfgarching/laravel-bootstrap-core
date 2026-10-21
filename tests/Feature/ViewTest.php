<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewTest extends TestCase
{
    use RefreshDatabase;

    /** @avoid */
    // public function test_auth_login_view_can_be_rendered(): void
    // {
    //     $view = $this->view('auth::login');

    //     $view->assertSee('Login');
    //     $view->assertSee('Email');
    //     $view->assertSee('Password');
    //     $view->assertSee('Remember me');
    // }

    /** @avoid */
    // public function test_auth_register_view_can_be_rendered(): void
    // {
    //     $view = $this->view('auth::register');

    //     $view->assertSee('Register');
    //     $view->assertSee('Name');
    //     $view->assertSee('Email');
    //     $view->assertSee('Password');
    //     $view->assertSee('Confirm Password');
    // }

    public function test_dashboard_view_displays_user_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $view = $this->actingAs($user)->view('page::dashboard');

        $view->assertSee('Dashboard');
        $view->assertSee('Welcome, Jane Doe');
        $view->assertSee('jane@example.com');
        $view->assertSee('Account Information');
        $view->assertSee('Logout');
    }

    public function test_guest_layout_is_used_in_auth_views(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        // Guest layout should not have sidebar or complex navigation
        $response->assertDontSee('offcanvas');
    }

    public function test_main_layout_is_used_in_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
        // Main layout should have full structure
        $response->assertSee('Dashboard');
    }

    public function test_login_view_has_register_link(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Register');
        $response->assertSee(route('register'));
    }

    public function test_register_view_has_login_link(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Login');
        $response->assertSee(route('login'));
    }

    public function test_views_use_correct_namespaces(): void
    {
        // Test that auth namespace works
        $this->assertTrue(view()->exists('auth::login'));
        $this->assertTrue(view()->exists('auth::register'));

        // Test that page namespace works
        $this->assertTrue(view()->exists('page::dashboard'));
        $this->assertTrue(view()->exists('page::admin'));
        $this->assertTrue(view()->exists('page::startpage'));
        $this->assertTrue(view()->exists('page::guestpage'));
        $this->assertTrue(view()->exists('page::testpage'));
        $this->assertTrue(view()->exists('page::testform'));
        $this->assertTrue(view()->exists('page::testing'));

        // Test that layout namespace works
        $this->assertTrue(view()->exists('layout::main'));
        $this->assertTrue(view()->exists('layout::guest'));
    }

    public function test_error_messages_are_displayed(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();

        $response = $this->get(route('login'));
        $response->assertSee('The provided credentials do not match our records');
    }
}
