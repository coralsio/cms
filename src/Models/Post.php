<?php

namespace Corals\Modules\CMS\Models;

use Corals\Utility\Comment\Traits\ModelHasComments;
use Corals\Utility\Tag\Traits\HasTags;
use Illuminate\Database\Eloquent\Builder;

class Post extends Content
{
    use HasTags;
    use ModelHasComments;

    public function getModuleName()
    {
        return 'CMS';
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'post');
        });
    }

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'cms.models.post';

    protected $attributes = [
        'type' => 'post',
    ];

    protected $fillable = ['id', 'title', 'slug', 'meta_keywords', 'tags', 'translation_language_code',
        'meta_description', 'content', 'published', 'published_at', 'private', 'internal', 'type', 'author_id', 'featured_image_link', ];

    public static function getFeedItems()
    {
        return Post::published()->public()->latest('published_at')->get();
    }
}
