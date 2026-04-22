<?php

namespace Gingerminds\LaravelCore\Http\Requests\User;

use Gingerminds\LaravelCore\Http\Requests\FormRequestInterface;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest implements FormRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = Auth::user();

        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user ? $user->id : null),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'contributor_firstname' => 'nullable|string|max:255',
            'contributor_lastname'  => 'nullable|string|max:255',
            'contributor_trigram'   => 'nullable|string|max:50',
            'contributor_civility'  => 'nullable|in:mr,mrs',
        ];
    }
}
