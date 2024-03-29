<?php

// CMS
Breadcrumbs::register('cms', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push(trans('cms::module.cms.title'));
});

//Page
Breadcrumbs::register('pages', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('cms::module.page.title'), url(config('cms.models.page.resource_url')));
});

Breadcrumbs::register('page_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('pages');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('page_show', function ($breadcrumbs) {
    $breadcrumbs->parent('pages');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Post
Breadcrumbs::register('posts', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('cms::module.post.title'), url(config('cms.models.post.resource_url')));
});

Breadcrumbs::register('post_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('posts');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('post_show', function ($breadcrumbs) {
    $breadcrumbs->parent('posts');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//News
Breadcrumbs::register('news', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('cms::module.news.title'), url(config('cms.models.news.resource_url')));
});

Breadcrumbs::register('news_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('news');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('news_show', function ($breadcrumbs) {
    $breadcrumbs->parent('news');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Faqs
Breadcrumbs::register('faqs', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('cms::module.faq.title'), url(config('cms.models.faq.resource_url')));
});

Breadcrumbs::register('faq_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('faqs');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('faq_show', function ($breadcrumbs) {
    $breadcrumbs->parent('faqs');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Testimonials
Breadcrumbs::register('testimonials', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('cms::module.testimonial.title'), url(config('cms.models.testimonial.resource_url')));
});

Breadcrumbs::register('testimonial_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('testimonials');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('testimonial_show', function ($breadcrumbs) {
    $breadcrumbs->parent('testimonials');
    $breadcrumbs->push(view()->shared('title_singular'));
});


// Download
Breadcrumbs::register('downloads', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('cms::module.download.title'), url(config('cms.models.download.resource_url')));
});

Breadcrumbs::register('download_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('downloads');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('download_show', function ($breadcrumbs) {
    $breadcrumbs->parent('downloads');
    $breadcrumbs->push(view()->shared('title_singular'));
});

//Category
Breadcrumbs::register('categories', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('cms::module.category.title'), url(config('cms.models.category.resource_url')));
});

Breadcrumbs::register('category_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('categories');
    $breadcrumbs->push(view()->shared('title_singular'));
});
//Block
Breadcrumbs::register('blocks', function ($breadcrumbs) {
    $breadcrumbs->parent('cms');
    $breadcrumbs->push(trans('cms::module.block.title'), url(config('cms.models.block.resource_url')));
});

Breadcrumbs::register('block_create_edit', function ($breadcrumbs) {
    $breadcrumbs->parent('blocks');
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('block_show', function ($breadcrumbs) {
    $breadcrumbs->parent('blocks');
    $breadcrumbs->push(view()->shared('title_singular'));
});
//Widget
Breadcrumbs::register(
    'widgets',
    function ($breadcrumbs, $block) {
        $breadcrumbs->parent('blocks');
        $breadcrumbs->push(trans('cms::module.widget.title', ['block' => $block->name]), route(config('cms.models.widget.resource_route'), ['block' => $block->hashed_id]));
    }
);

Breadcrumbs::register('widget_create_edit', function ($breadcrumbs, $block) {
    $breadcrumbs->parent('widgets', $block);
    $breadcrumbs->push(view()->shared('title_singular'));
});

Breadcrumbs::register('widget_show', function ($breadcrumbs, $block) {
    $breadcrumbs->parent('widgets', $block);
    $breadcrumbs->push(view()->shared('title_singular'));
});
