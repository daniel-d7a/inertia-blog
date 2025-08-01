<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\TagValidationRules;

class UpdatePostRequest extends FormRequest
{
    use TagValidationRules;

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
        return array_merge([
            "title" => "filled|max:255",
            "body" => "filled",
            "banner" => "nullable|image|max:2048",
        ], $this->tagRules());
    }
}
