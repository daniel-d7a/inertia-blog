<?php

namespace App\Http\Requests;

use App\Enums\VoteableType;
use App\Enums\VoteType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoteRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'voteable_id' => 'required',
            'voteable_type' => ['required', Rule::in(VoteableType::toValues())],
            'vote_type' => ['required', Rule::in(VoteType::toValues())],
            'redirect_to' => 'required|string'
        ];
    }
}
