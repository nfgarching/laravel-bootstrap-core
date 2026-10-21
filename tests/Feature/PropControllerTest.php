<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nfgarching\Core\Models\Prop;
use Tests\TestCase;

class PropControllerTest extends TestCase
{
    use RefreshDatabase;

    private function user(): User
    {
        return User::factory()->create();
    }

    private function prop(array $attributes = []): Prop
    {
        return Prop::factory()->create($attributes);
    }

    // -------------------------------------------------------------------------
    // index
    // -------------------------------------------------------------------------

    public function test_index_requires_authentication(): void
    {
        $this->get(route('core.props.index'))
            ->assertRedirect(route('login'));
    }

    public function test_index_returns_view(): void
    {
        $this->actingAs($this->user())
            ->get(route('core.props.index'))
            ->assertOk()
            ->assertViewIs('page::props.index');
    }

    public function test_index_lists_props(): void
    {
        Prop::factory(3)->create();

        $this->actingAs($this->user())
            ->get(route('core.props.index'))
            ->assertOk()
            ->assertViewHas('props');
    }

    public function test_index_filters_by_state(): void
    {
        Prop::factory()->create(['state' => 'active']);
        Prop::factory()->create(['state' => 'error']);

        $response = $this->actingAs($this->user())
            ->get(route('core.props.index', ['state' => 'active']));

        $response->assertOk();
        $props = $response->viewData('props');
        $this->assertTrue($props->every(fn ($p) => $p->state === 'active'));
    }

    public function test_index_filters_by_type(): void
    {
        Prop::factory()->create(['type' => 'iban']);
        Prop::factory()->create(['type' => 'birth_date']);

        $response = $this->actingAs($this->user())
            ->get(route('core.props.index', ['type' => 'iban']));

        $response->assertOk();
        $props = $response->viewData('props');
        $this->assertTrue($props->every(fn ($p) => $p->type === 'iban'));
    }

    public function test_index_paginates(): void
    {
        Prop::factory(12)->create();

        $response = $this->actingAs($this->user())
            ->get(route('core.props.index'));

        $props = $response->viewData('props');
        $this->assertCount(8, $props->items());
    }

    // -------------------------------------------------------------------------
    // create
    // -------------------------------------------------------------------------

    public function test_create_requires_authentication(): void
    {
        $this->get(route('core.props.create'))
            ->assertRedirect(route('login'));
    }

    public function test_create_returns_view(): void
    {
        $this->actingAs($this->user())
            ->get(route('core.props.create'))
            ->assertOk()
            ->assertViewIs('page::props.create');
    }

    // -------------------------------------------------------------------------
    // store – create new prop
    // -------------------------------------------------------------------------

    public function test_store_requires_authentication(): void
    {
        $this->post(route('core.props.store'), [])
            ->assertRedirect(route('login'));
    }

    public function test_store_creates_prop_and_redirects_to_show(): void
    {
        $data = [
            'model_type' => 'App\Models\User',
            'model_id' => 1,
            'state' => 'active',
            'type' => 'iban',
            'key' => 'test_key',
            'label' => 'Test Label',
            'value' => 'DE89370400440532013000',
            'memo' => 'Ein Memo',
        ];

        $response = $this->actingAs($this->user())
            ->post(route('core.props.store'), $data);

        $prop = Prop::latest()->first();
        $response->assertRedirect(route('core.props.show', $prop));
        $this->assertDatabaseHas('props', ['key' => 'test_key', 'value' => 'DE89370400440532013000']);
    }

    // -------------------------------------------------------------------------
    // store – bulk change
    // -------------------------------------------------------------------------

    public function test_store_change_updates_state_for_checked_props(): void
    {
        $prop1 = $this->prop(['state' => 'pending']);
        $prop2 = $this->prop(['state' => 'pending']);

        $this->actingAs($this->user())
            ->post(route('core.props.store'), [
                'switchTo' => 'change',
                'checks' => [$prop1->id, $prop2->id],
                'state' => 'active',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('props', ['id' => $prop1->id, 'state' => 'active']);
        $this->assertDatabaseHas('props', ['id' => $prop2->id, 'state' => 'active']);
    }

    public function test_store_change_updates_type_for_checked_props(): void
    {
        $prop = $this->prop(['type' => 'iban']);

        $this->actingAs($this->user())
            ->post(route('core.props.store'), [
                'switchTo' => 'change',
                'checks' => [$prop->id],
                'type' => 'birth_date',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('props', ['id' => $prop->id, 'type' => 'birth_date']);
    }

    public function test_store_throws_for_unknown_switch_to(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('switchTo nicht programmiert: unknown');

        $this->withoutExceptionHandling()
            ->actingAs($this->user())
            ->post(route('core.props.store'), ['switchTo' => 'unknown']);
    }

    // -------------------------------------------------------------------------
    // show
    // -------------------------------------------------------------------------

    public function test_show_requires_authentication(): void
    {
        $prop = $this->prop();

        $this->get(route('core.props.show', $prop))
            ->assertRedirect(route('login'));
    }

    public function test_show_returns_view_with_prop(): void
    {
        $prop = $this->prop();

        $this->actingAs($this->user())
            ->get(route('core.props.show', $prop))
            ->assertOk()
            ->assertViewIs('page::props.show')
            ->assertViewHas('prop', $prop);
    }

    // -------------------------------------------------------------------------
    // edit
    // -------------------------------------------------------------------------

    public function test_edit_requires_authentication(): void
    {
        $prop = $this->prop();

        $this->get(route('core.props.edit', $prop))
            ->assertRedirect(route('login'));
    }

    public function test_edit_returns_view_with_prop(): void
    {
        $prop = $this->prop();

        $this->actingAs($this->user())
            ->get(route('core.props.edit', $prop))
            ->assertOk()
            ->assertViewIs('page::props.edit')
            ->assertViewHas('prop', $prop);
    }

    // -------------------------------------------------------------------------
    // update
    // -------------------------------------------------------------------------

    public function test_update_requires_authentication(): void
    {
        $prop = $this->prop();

        $this->put(route('core.props.update', $prop), [])
            ->assertRedirect(route('login'));
    }

    public function test_update_saves_changes_and_redirects_to_show(): void
    {
        $prop = $this->prop(['state' => 'pending', 'value' => 'old']);

        $this->actingAs($this->user())
            ->put(route('core.props.update', $prop), [
                'state' => 'active',
                'value' => 'new',
            ])
            ->assertRedirect(route('core.props.show', $prop));

        $this->assertDatabaseHas('props', ['id' => $prop->id, 'state' => 'active', 'value' => 'new']);
    }
}
