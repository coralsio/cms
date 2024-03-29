<?php

namespace Tests\Feature;

use Corals\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class FaqsViewTest extends TestCase
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
    public function test_faqs_view()
    {
        $response = $this->get('cms/faqs');

        $response->assertStatus(200)->assertViewIs('cms::faqs.index');
    }

    public function test_faqs_create()
    {
        $response = $this->get('cms/faqs/create');

        $response->assertViewIs('cms::faqs.create_edit')->assertStatus(200);
    }

    public function test_faqs_bulk_action()
    {
        $response = $this->post('cms/faqs/bulk-action', [
            'action' => 'published',]);

        $response->assertSeeText('message');
    }
}
