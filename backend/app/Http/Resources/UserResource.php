<?php

namespace App\Http\Resources;

/**
 * @mixin \App\Models\User
 */
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \App\Models\User $user */
        $user = $this->resource;

        return [
            'id' => $user->getAttribute('id'),
            'first_name' => $user->getAttribute('first_name'),
            'last_name' => $user->getAttribute('last_name'),
            'company_name' => $user->getAttribute('company_name'),
            'email' => $user->getAttribute('email'),
            'contact_number' => $user->getAttribute('contact_number'),
            'description' => $user->getAttribute('description'),
            'created_at' => $user->getAttribute('created_at'),
            'updated_at' => $user->getAttribute('updated_at'),
            'deleted_at' => $user->getAttribute('deleted_at'),
            'restored_at' => $user->getAttribute('restored_at'),
        ];
    }
}
