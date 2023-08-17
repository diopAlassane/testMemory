<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AdminStoreRequest;
use App\Http\Requests\AdminUpdateRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Admin::class);

        $search = $request->get('search', '');

        $admins = Admin::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.admins.index', compact('admins', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Admin::class);

        $users = User::pluck('name', 'id');

        return view('app.admins.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Admin::class);

        $validated = $request->validated();

        $admin = Admin::create($validated);

        return redirect()
            ->route('admins.edit', $admin)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Admin $admin): View
    {
        $this->authorize('view', $admin);

        return view('app.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Admin $admin): View
    {
        $this->authorize('update', $admin);

        $users = User::pluck('name', 'id');

        return view('app.admins.edit', compact('admin', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        AdminUpdateRequest $request,
        Admin $admin
    ): RedirectResponse {
        $this->authorize('update', $admin);

        $validated = $request->validated();

        $admin->update($validated);

        return redirect()
            ->route('admins.edit', $admin)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Admin $admin): RedirectResponse
    {
        $this->authorize('delete', $admin);

        $admin->delete();

        return redirect()
            ->route('admins.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
