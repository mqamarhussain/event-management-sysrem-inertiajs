<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->authorize("create_user", User::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules()
    {
        return [
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', 'unique:users'],
            'password' => ['nullable'],
            'photo' => ['nullable', 'image'],
            'roles' => ['required', 'array'], // Ensure roles are provided and are in array format
            'roles.*' => [Rule::in(Role::pluck('id'))], // Validate each role ID against existing roles
        ];
    }
}
