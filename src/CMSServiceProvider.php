<?php

namespace Corals\Modules\CMS;

use Corals\Foundation\Providers\BasePackageServiceProvider;
use Corals\Modules\CMS\Facades\CMS;
use Corals\Modules\CMS\Facades\OpenGraph;
use Corals\Modules\CMS\Facades\SEOMeta;
use Corals\Modules\CMS\Facades\SEOTools;
use Corals\Modules\CMS\Facades\TwitterCard;
use Corals\Modules\CMS\Hooks\CMS as CMSHook;
use Corals\Modules\CMS\Http\Controllers\FeedController;
use Corals\Modules\CMS\Models\Block;
use Corals\Modules\CMS\Models\Category;
use Corals\Modules\CMS\Models\Faq;
use Corals\Modules\CMS\Models\News;
use Corals\Modules\CMS\Models\Page;
use Corals\Modules\CMS\Models\Post;
use Corals\Modules\CMS\Providers\CMSAuthServiceProvider;
use Corals\Modules\CMS\Providers\CMSObserverServiceProvider;
use Corals\Modules\CMS\Providers\CMSRouteServiceProvider;
use Corals\Modules\CMS\Providers\SEOToolsServiceProvider;
use Corals\Settings\Facades\Modules;
use Corals\Settings\Facades\Settings;
use Corals\Utility\Facades\Utility;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\View;

class CMSServiceProvider extends BasePackageServiceProvider
{
    /**
     * @var
     */
    protected $defer = true;
    /**
     * @var
     */
    protected $packageCode = 'corals-cms';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function bootPackage()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'cms');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'cms');

        //Register Widgets
        $this->registerWidgets();
        $this->registerCustomFieldsModels();

        \Filters::add_filter('dashboard_content', [CMSHook::class, 'dashboard_content1'], 15);
        //\Filters::add_filter('dashboard_content', [CMSHook::class, 'dashboard_content2'], 25);

        $this->registerShortcode();

        $this->registerFeedLinksComposer();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerPackage()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/cms.php', 'cms');
        $this->mergeConfigFrom(__DIR__ . '/config/feed.php', 'feed');
        $this->registerFeedRouteMacro();

        $this->app->register(CMSRouteServiceProvider::class);
        $this->app->register(CMSAuthServiceProvider::class);
        $this->app->register(CMSObserverServiceProvider::class);
        $this->app->register(SEOToolsServiceProvider::class);

        //register aliases instead of adding it to config/app.php
        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('CMS', CMS::class);
            $loader->alias('SEOMeta', SEOMeta::class);
            $loader->alias('OpenGraph', OpenGraph::class);
            $loader->alias('Twitter', TwitterCard::class);
            $loader->alias('SEO', SEOTools::class);
        });


        Utility::addToUtilityModules('CMS');
    }

    public function registerFeedRouteMacro()
    {
        $router = $this->app['router'];

        $router->macro('feeds', function ($baseUrl = '') use ($router) {
            foreach (config('feed.feeds') as $name => $configuration) {
                $url = CMS::mergeURLPath($baseUrl, $configuration['url']);

                $router->get($url, '\\' . FeedController::class)->name("feeds.{$name}");
            }
        });
    }

    public function registerFeedLinksComposer()
    {
        View::composer('cms::feed.links', function ($view) {
            $view->with('feeds', $this->feeds());
        });
    }

    protected function feeds()
    {
        return collect(config('feed.feeds'));
    }

    public function registerWidgets()
    {
        \Shortcode::addWidget('cms', \Corals\Modules\CMS\Widgets\CMSWidget::class);
        \Shortcode::addWidget('page_views', \Corals\Modules\CMS\Widgets\PageViewsWidget::class);
        \Shortcode::addWidget('current_visitors', \Corals\Modules\CMS\Widgets\CurrentVisitorCountWidget::class);
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(Post::class);
        Settings::addCustomFieldModel(Page::class);
        Settings::addCustomFieldModel(News::class);
        Settings::addCustomFieldModel(Faq::class);
        Settings::addCustomFieldModel(Category::class);
    }

    public function registerShortcode()
    {
        \Shortcode::add('block', function ($key) {
            $view = 'cms::blocks.block';

            if ($key == '$block') {
                //Assume Block Object passed
                return "<?php  echo \$__env->make('$view',['block'=>{$key}])->render(); ?>";
            } else {
                $block = Block::where('key', $key)->active()->first();
            }

            $block = serialize($block);

            if (view()->exists($view)) {
                return "<?php  echo \$__env->make('$view',['block'=>'{$block}','block_key'=>'{$key}'])->render(); ?>";
            }
        });
    }

    public function registerModulesPackages()
    {
        Modules::addModulesPackages('corals/cms');
    }
}
