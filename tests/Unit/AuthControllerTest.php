<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Nfgarching\Core\Controllers\Auth\AuthenticatedSessionController;
use Nfgarching\Core\Controllers\Auth\RegisteredUserController;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_session_controller_create_returns_view(): void
    {
        $controller = new AuthenticatedSessionController;

        $view = $controller->create();

        $this->assertEquals('auth::login', $view->name());
    }

    public function test_registered_user_controller_create_returns_view(): void
    {
        $controller = new RegisteredUserController;

        $view = $controller->create();

        $this->assertEquals('auth::register', $view->name());
    }

    public function test_user_password_is_hashed_on_registration(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        $this->assertNotNull($user);
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_session_is_regenerated_on_login(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_session_is_invalidated_on_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_failed_login_returns_to_login_page_with_errors(): void
    {
        $response = $this->post(route('login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }
}
