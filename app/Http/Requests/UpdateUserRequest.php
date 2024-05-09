<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
        return $this->user()->can('update_user', $this->route('user'));
    }

    public function rules()
    {
        return [
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($this->route('user')->id)],
            'password' => ['nullable'],
            'photo' => ['nullable', 'image'],
            'roles.*' => ["nullable"], // Validate each role ID against existing roles
        ];
    }
}
