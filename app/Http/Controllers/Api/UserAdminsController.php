<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Http\Resources\AdminCollection;

class UserAdminsController extends Controller
{
    public function index(Request $request, User $user): AdminCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $admins = $user
            ->admins()
            ->search($search)
            ->latest()
            ->paginate();

        return new AdminCollection($admins);
    }

    public function store(Request $request, User $user): AdminResource
    {
        $this->authorize('create', Admin::class);

        $validated = $request->validate([]);

        $admin = $user->admins()->create($validated);

        return new AdminResource($admin);
    }
}
