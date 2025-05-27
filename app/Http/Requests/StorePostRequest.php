<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
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

        // existing tags
        // [x] array name, id
        // [x] unique id
        // [x] exists in db

        // new tags
        // [x] array full of strings
        // [x] must be all unique
        // [x] must have unique db names 

        return [
            "title" => "required|max:255",
            "body" => "required",
            'newTags' => 'nullable|array',
            'newTags.*' => ["string", "max:100", "unique:tags,name", "distinct:ignore_case"],
            // TODO:validate array keys
            'existingTags' => 'nullable|array',
            'existingTags.*.id' => ['exists:tags,id', 'distinct'],
        ];
    }
}
