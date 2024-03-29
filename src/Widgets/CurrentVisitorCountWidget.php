<?php

namespace Corals\Modules\CMS\Widgets;

use Corals\Modules\CMS\Charts\CurrentVisitors;

class CurrentVisitorCountWidget
{
    public function __construct()
    {
    }

    public function run($args)
    {
        try {
            $chart = new CurrentVisitors();

            $api = url('cms/active-users');

            $chart->labels(['First', 'Second', 'Third'])
                ->load($api);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
