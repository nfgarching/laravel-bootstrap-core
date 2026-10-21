<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BasicTest extends TestCase
{
    // use DatabaseMigrations;
    use RefreshDatabase;

    public function test_welcome_page(): void
    {
        // Run the DatabaseSeeder...
        // $this->seed();

        // Given

        // When
        $response = $this->get('/welcome');

        // Then
        $response->assertStatus(200);

        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();

        // $response->ddHeaders();
        // $response->ddSession();
        // $response->dd();
    }

    public function test_can_show_startpage(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/')
            ->assertOk();
    }

    /** @avoid */
    public function test_can_show_adminpages(): void
    {
        // $user = User::factory()->create();
        // Auth::login($user);

        $this->actingAs(User::factory()->create());

        // $this->get('/')->assertStatus(200);
        // $this->get('/dashboard')->assertOk();

        // zu Filament
        // $response = $this->get('/admin');
        // $response->assertOk();

        $response = $this->get('/admin');
        $response->assertOk();

        // $response = $this->get('/admin/names');
        // $response->assertOk();

        // $response = $this->get('/admin/registers');
        // $response->assertOk();

    }

    public function test_can_show_mainpages_without_login(): void
    {
        $this->get('/guestpage')
            ->assertOk();

        // $response = $this->get('/admin/names');
        // $response->assertOk();

        // $response = $this->get('/admin/registers');
        // $response->assertOk();

    }

    public function test_can_show_mainpages(): void
    {

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();

        $this->get('/startpage')
            ->assertOk();

        $this->get('/testing')
            ->assertOk();

        $this->get('/testpage')
            ->assertOk();

        $this->get('/welcome')
            ->assertOk();

        $this->actingAs($user)
            ->get('/welcome')
            ->assertOk();

        // $response = $this->get('/dashboard');
        // $response->assertOk();

        // $response = $this->get('/startpage');
        // $response->assertOk();

        // $response = $this->get('/testing');
        // $response->assertOk();

        // $response = $this->get('/testpage');
        // $response->assertOk();

        // $response = $this->get('/welcome');
        // $response->assertOk();
    }

    /** @avoid */
    public function test_can_not_show_mainpages_without_login(): void
    {

        $response = $this->get('/dashboard');
        $response->assertFound();
        $response->assertRedirect(route('login'));
        $this->assertGuest();

        // $response = $this->get('/admin/names');
        // $response->assertOk();

        // $response = $this->get('/admin/registers');
        // $response->assertOk();

    }

    /** @avoid */
    public function interacting_with_headers(): void
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('/user', ['name' => 'Sally']);

        $response->assertStatus(404);
    }

    /** @avoid */
    public function interacting_with_cookies(): void
    {
        $response = $this->withCookie('color', 'blue')->get('/');

        $response = $this->withCookies([
            'color' => 'blue',
            'name' => 'Taylor',
        ])->get('/');

        $response->assertStatus(200);
    }

    /** @avoid */
    public function basic_test_with_dd_and_dump(): void
    {
        $response = $this->get('/');
        // $response->ddHeaders();
        // $response->ddSession();
        // $response->dd();
        // $response->dumpHeaders();
        // $response->dumpSession();
        // $response->dump();
        $response->assertStatus(200);
    }

    /** @avoid */
    public function avatars_can_be_uploaded(): void
    {
        // Storage::fake('avatars');
        // $file = UploadedFile::fake()->image('avatar.jpg');
        // $response = $this->post('/avatar', [
        //     'avatar' => $file,
        // ]);
        // Storage::disk('avatars')->assertExists($file->hashName());

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }

    /** @avoid */
    public function a_welcome_view_can_be_rendered(): void
    {
        $view = $this->view('/welcome', ['name' => 'Taylor']);
        $view->assertSee('Laravel');
    }
}
