<?php

namespace Corals\Modules\CMS\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\CMS\Models\Widget;

class WidgetTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_route = config('cms.models.widget.resource_route');

        parent::__construct($extras);
    }

    /**
     * @param Widget $widget
     * @return array
     * @throws \Throwable
     */
    public function transform(Widget $widget)
    {
        $url = route($this->resource_route, ['block' => $widget->block->hashed_id]);

        $transformedArray = [
            'id' => $widget->id,
            'widget_order' => $widget->widget_order,
            'title' => \Str::limit($widget->title, 50),
            'widget_width' => $widget->widget_width,
            'status' => formatStatusAsLabels($widget->status),
            'created_at' => format_date($widget->created_at),
            'updated_at' => format_date($widget->updated_at),
            'action' => $this->actions($widget, [], $url),
        ];

        return parent::transformResponse($transformedArray);
    }
}
