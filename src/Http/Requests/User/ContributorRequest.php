<?php

namespace Gingerminds\LaravelCore\Http\Requests\User;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ContributorRequest extends FormRequest implements FormRequestInterface
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstname'           => 'nullable|string|max:255',
            'lastname'            => 'nullable|string|max:255',
            'trigram'             => 'nullable|string|max:50',
            'civility'            => 'nullable|in:mr,mrs',
            'user_id'             => 'nullable|integer|exists:users,id',
        ];
    }
}
