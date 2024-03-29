<?php

namespace Corals\Modules\CMS\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\CMS\Models\Page;
use Corals\Modules\CMS\Transformers\PageTransformer;
use Yajra\DataTables\EloquentDataTable;

class PagesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('cms.models.page.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new PageTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Page $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Page $model)
    {
        return $model->newQuery();
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
            'private' => ['title' => trans('cms::attributes.content.private')],
            'internal' => ['title' => trans('cms::attributes.content.internal')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    public function getFilters()
    {
        return [
            'title' => ['title' => trans('cms::attributes.content.title'), 'class' => 'col-md-3', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'slug' => ['title' => trans('cms::attributes.content.slug'), 'class' => 'col-md-2', 'type' => 'text', 'condition' => 'like', 'active' => true],
            'created_at' => ['title' => trans('Corals::attributes.created_at'), 'class' => 'col-md-2', 'type' => 'date', 'active' => true],
            'published' => ['title' => trans('cms::attributes.content.published'), 'class' => 'col-md-2', 'type' => 'boolean', 'active' => true],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Corals::labels.delete'), 'permission' => 'CMS::page.delete', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'published' => ['title' => '<i class="fa fa-check-circle"></i> ' . trans('cms::attributes.content.published'), 'permission' => 'CMS::page.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'draft' => ['title' => '<i class="fa fa-check-circle-o"></i> ' . trans('cms::attributes.content.draft'), 'permission' => 'CMS::page.update', 'confirmation' => trans('Corals::labels.confirmation.title')],

        ];
    }

    protected function getOptions()
    {
        $url = url(config('cms.models.page.resource_url'));

        return ['resource_url' => $url];
    }
}
