<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use App\Http\Resources\AdminCollection;
use App\Http\Requests\AdminStoreRequest;
use App\Http\Requests\AdminUpdateRequest;

class AdminController extends Controller
{
    public function index(Request $request): AdminCollection
    {
        $this->authorize('view-any', Admin::class);

        $search = $request->get('search', '');

        $admins = Admin::search($search)
            ->latest()
            ->paginate();

        return new AdminCollection($admins);
    }

    public function store(AdminStoreRequest $request): AdminResource
    {
        $this->authorize('create', Admin::class);

        $validated = $request->validated();

        $admin = Admin::create($validated);

        return new AdminResource($admin);
    }

    public function show(Request $request, Admin $admin): AdminResource
    {
        $this->authorize('view', $admin);

        return new AdminResource($admin);
    }

    public function update(
        AdminUpdateRequest $request,
        Admin $admin
    ): AdminResource {
        $this->authorize('update', $admin);

        $validated = $request->validated();

        $admin->update($validated);

        return new AdminResource($admin);
    }

    public function destroy(Request $request, Admin $admin): Response
    {
        $this->authorize('delete', $admin);

        $admin->delete();

        return response()->noContent();
    }
}
