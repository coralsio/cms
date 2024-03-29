<?php
/**
 * Created by PhpStorm.
 * User: iMak
 * Date: 11/19/17
 * Time: 9:12 AM
 */

namespace Corals\Modules\CMS\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\CMS\Models\News;

class NewsRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(News::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(News::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'title' => 'required|max:191',
                'content' => 'required',
                'featured_image' => 'mimes:jpg,jpeg,png|max:' . maxUploadFileSize(),
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'slug' => 'required|max:191|unique:posts,slug',
            ]);
        }

        if ($this->isUpdate()) {
            $news = $this->route('news');

            $rules = array_merge($rules, [
                'slug' => 'required|max:191|unique:posts,slug,' . $news->id,
            ]);
        }

        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        if ($this->isUpdate() || $this->isStore()) {
            $data = $this->all();

            if (isset($data['slug'])) {
                $data['slug'] = \Str::slug($data['slug']);
            }

            $data['published'] = \Arr::get($data, 'published', false);
            $data['private'] = \Arr::get($data, 'private', false);
            $data['internal'] = \Arr::get($data, 'internal', false);

            if ($this->isStore()) {
                $data['author_id'] = user()->id;
            }

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}
