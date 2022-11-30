<?php
/**
 * Created by PhpStorm.
 * User: iMak
 * Date: 11/19/17
 * Time: 9:00 AM
 */

namespace Corals\Modules\CMS\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\CMS\Models\News;
use Corals\Modules\CMS\Transformers\NewsTransformer;
use Yajra\DataTables\EloquentDataTable;

class NewsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('cms.models.news.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new NewsTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param News $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(News $model)
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
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Corals::labels.delete'), 'permission' => 'CMS::news.delete', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'published' => ['title' => '<i class="fa fa-check-circle"></i> ' .trans('cms::attributes.content.published'), 'permission' => 'CMS::news.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'draft' => ['title' => '<i class="fa fa-check-circle-o"></i> ' .trans('cms::attributes.content.draft'), 'permission' => 'CMS::news.update', 'confirmation' => trans('Corals::labels.confirmation.title')]
        ];
    }

    protected function getOptions()
    {
        $url = url(config('cms.models.news.resource_url'));
        return ['resource_url' => $url];
    }
}
