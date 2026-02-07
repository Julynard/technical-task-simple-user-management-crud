<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $query = User::query()->latest();

        if ($search) {
            $searchTerm = '%'.$search.'%';
            $query->where(function ($builder) use ($searchTerm) {
                $builder
                    ->where('first_name', 'like', $searchTerm)
                    ->orWhere('last_name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    public function findTrashedByEmail(string $email): ?User
    {
        return User::onlyTrashed()->where('email', $email)->first();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }
}
