<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(private readonly UserService $users) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->integer('per_page', 10);
        $search = $request->string('q')->trim()->toString();

        $users = $this->users->paginate($perPage, $search ?: null);

        return UserResource::collection($users)->additional([
            'success' => true,
            'message' => 'Users retrieved successfully',
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $result = $this->users->create($request->validated());
        $user = $result['user'];
        $restored = $result['restored'];

        if ($restored) {
            return response()->json([
                'success' => true,
                'message' => 'User reactivated successfully',
                'data' => UserResource::make($user),
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => UserResource::make($user),
        ], 201);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => UserResource::make($user),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user = $this->users->update($user, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => UserResource::make($user),
        ]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->users->delete($user);

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
            'data' => null,
        ]);
    }
}
