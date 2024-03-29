<?php

namespace Tests\Feature;

use Corals\Modules\CMS\Models\Category;
use Corals\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use DatabaseTransactions;

    protected $categoryRequest;
    protected $category;
    protected $belongs_to;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'superuser');
        })->first();
        Auth::loginUsingId($user->id);
    }

    public function test_category_store()
    {
        $categories = ['category1', 'category2', 'category3', 'category4'];
        $category = array_rand($categories);
        $this->belongs_to = [
            'page' => trans('cms::attributes.category.page'),
            'post' => trans('cms::attributes.category.post'),
            'faq' => trans('cms::attributes.category.faq'),
        ];

        $this->categoryRequest = [
            'name' => $categories[$category],
            'slug' => $categories[$category],
            'belongs_to' => array_rand($this->belongs_to),
            'status' => 'active',
        ];

        $response = $this->post('cms/categories', $this->categoryRequest);

        $this->category = Category::query()->where([
            ['name', $this->categoryRequest['name']],
            ['slug', $this->categoryRequest['slug']],
            ['belongs_to', $this->categoryRequest['belongs_to'],
            ['status', 'active'], ],
        ])->first();

        $response->assertDontSee('The given data was invalid')
            ->assertRedirect('cms/categories');

        $this->assertDatabaseHas('categories', [
            'name' => $this->category->name,
            'slug' => $this->category->slug,
            'belongs_to' => $this->category->belongs_to,
            'status' => $this->category->status,
        ]);
    }

    public function test_category_edit()
    {
        $this->test_category_store();
        if ($this->category) {
            $response = $this->get('cms/categories/' . $this->category->hashed_id . '/edit');

            $response->assertViewIs('cms::categories.create_edit')->assertStatus(200);
        }
        $this->assertTrue(true);
    }

    public function test_category_update()
    {
        $this->test_category_store();

        if ($this->category) {
            $belong = array_rand($this->belongs_to);
            $response = $this->put('cms/categories/' . $this->category->hashed_id, [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'belongs_to' => $belong,
                'status' => $this->category->status,
            ]);

            $response->assertRedirect('cms/categories');
            $this->assertDatabaseHas('categories', [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'belongs_to' => $belong,
                'status' => $this->category->status,
            ]);
        }

        $this->assertTrue(true);
    }

    public function test_category_delete()
    {
        $this->test_category_store();

        if ($this->category) {
            $response = $this->delete('cms/categories/' . $this->category->hashed_id);

            $response->assertStatus(200)->assertSeeText('Category has been deleted successfully.');

            $this->isSoftDeletableModel(Category::class);
            $this->assertDatabaseMissing('categories', [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
                'belongs_to' => $this->category->belongs_to,
                'status' => $this->category->status,]);
        }
        $this->assertTrue(true);
    }
}
