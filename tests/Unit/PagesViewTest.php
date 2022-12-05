<?php

namespace Tests\Feature;

use Corals\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PagesViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'superuser');
        })->first();
        Auth::loginUsingId($user->id);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_pages_view()
    {
        $response = $this->get('cms/pages');

        $response->assertStatus(200)->assertViewIs('cms::pages.index');
    }

    public function test_pages_create()
    {
        $response = $this->get('cms/pages/create');

        $response->assertViewIs('cms::pages.create_edit')->assertStatus(200);
    }

    public function test_pages_bulk_action()
    {
        $response = $this->post('cms/pages/bulk-action', [
            'action' => 'published',]);

        $response->assertSeeText('message');
    }
}