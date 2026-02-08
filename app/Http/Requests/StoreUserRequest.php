<?php

namespace App\Http\Requests;

class StoreUserRequest extends ApiFormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL'],
            'contact_number' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
        ];
    }
}
