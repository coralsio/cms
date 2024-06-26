<?php

namespace Corals\Modules\CMS\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\CMS\Models\Post;

class PostRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Post::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Post::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'title' => 'required|max:191',
                'content' => 'required',
                'categories' => 'required',
                'featured_image' => 'mimes:jpg,jpeg,png|max:' . maxUploadFileSize(),
                'author_id'=>'required|exists:users,id'
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'slug' => 'required|max:191|unique:posts,slug',
            ]);
        }

        if ($this->isUpdate()) {
            $post = $this->route('post');

            $rules = array_merge($rules, [
                'slug' => 'required|max:191|unique:posts,slug,' . $post->id,
            ]);
        }

        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        $data = $this->all();

        if (isset($data['slug'])) {
            $data['slug'] = \Str::slug($data['slug']);
        }

        $data['published'] = \Arr::get($data, 'published', false);
        $data['private'] = \Arr::get($data, 'private', false);
        $data['internal'] = \Arr::get($data, 'internal', false);

        $this->getInputSource()->replace($data);

        return parent::getValidatorInstance();
    }
}
