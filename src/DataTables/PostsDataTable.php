<?php

namespace Corals\Modules\CMS\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\CMS\Facades\CMS;
use Corals\Modules\CMS\Models\Post;
use Corals\Modules\CMS\Transformers\PostTransformer;
use Yajra\DataTables\EloquentDataTable;

class PostsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('cms.models.post.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new PostTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Post $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Post $model)
    {
        return $model->with('categories');
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'title' => ['title' => trans('cms::attributes.content.title')],
            'slug' => ['title' => trans('cms::attributes.content.slug')],
            'published' => ['title' => trans('cms::attributes.content.published')],
            'published_at' => ['title' => trans('cms::attributes.content.published_at')],
            'categories' => ['name' => 'categories.name', 'title' => trans('cms::attributes.content.categories'), 'orderable' => false],
            'private' => ['title' => trans('cms::attributes.content.private')],
            'internal' => ['title' => trans('cms::attributes.content.internal')],
        ];
    }

    public function getFilters()
    {
        return [
            'title' => ['title' => trans('cms::attributes.content.title'), 'class' => 'col-md-4', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'slug' => ['title' => trans('cms::attributes.content.slug'), 'class' => 'col-md-3', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'categories.id' => ['title' => trans('cms::attributes.content.title'), 'class' => 'col-md-2', 'type' => 'select2', 'options' => CMS::getCategoriesList(false, null, null, 'post'), 'active' => true],
            'created_at' => ['title' => trans('Corals::attributes.created_at'), 'class' => 'col-md-2', 'type' => 'date', 'active' => true],
            'published' => ['title' => trans('cms::labels.post.show_published_only'), 'class' => 'col-md-2', 'type' => 'boolean', 'active' => true],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Corals::labels.delete'), 'permission' => 'CMS::post.delete', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'published' => ['title' => '<i class="fa fa-check-circle"></i> ' .trans('cms::attributes.content.published'), 'permission' => 'CMS::post.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'draft' => ['title' => '<i class="fa fa-check-circle-o"></i> ' .trans('cms::attributes.content.draft'), 'permission' => 'CMS::post.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
        ];
    }

    protected function getOptions()
    {
        $url = url(config('cms.models.post.resource_url'));

        return ['resource_url' => $url];
    }
}
