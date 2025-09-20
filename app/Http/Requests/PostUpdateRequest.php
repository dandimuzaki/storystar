<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,jpg,png,svg,gif|max:2048',
            'title' => 'required',
            'category_id' => 'required', 'exists:category,id',
            'content' => 'required',
            'published_at' => ['nullable', 'date', 'after:now']
        ];
    }
}
