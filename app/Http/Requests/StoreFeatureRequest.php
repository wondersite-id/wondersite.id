<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeatureRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'description' => 'required',
            'sequence_number' => 'integer|min:1',
            'image' => 'required|image|max:2048',
            'published_at' => 'nullable',

            // seo
            'seo_title' => 'required',
            'seo_description' => 'required',
            'seo_robots' => 'nullable',
            'seo_canonical_url' => 'nullable|url',
        ];
    }
}