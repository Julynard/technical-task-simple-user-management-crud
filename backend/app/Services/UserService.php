<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(private readonly UserRepositoryInterface $users) {}

    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function paginate(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->users->paginate($perPage, $search);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{user: User, restored: bool}
     */
    public function create(array $data): array
    {
        $trashedUser = $this->users->findTrashedByEmail($data['email']);

        if ($trashedUser) {
            $trashedUser->restore();
            $trashedUser->fill($data);
            $trashedUser->setAttribute('restored_at', now());
            $trashedUser->save();

            return [
                'user' => $trashedUser,
                'restored' => true,
            ];
        }

        return [
            'user' => $this->users->create($data),
            'restored' => false,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(User $user, array $data): User
    {
        return $this->users->update($user, $data);
    }

    public function delete(User $user): bool
    {
        return $this->users->delete($user);
    }
}
