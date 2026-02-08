<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateUserRequest extends ApiFormRequest
{
    /**
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        if (is_object($user) && method_exists($user, 'getKey')) {
            $userId = $user->getKey();
        } elseif (is_numeric($user)) {
            $userId = (int) $user;
        } else {
            $userId = null;
        }

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId)->whereNull('deleted_at'),
            ],
            'contact_number' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ];
    }
}
