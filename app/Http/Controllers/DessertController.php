<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Dessert;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DessertStoreRequest;
use App\Http\Requests\DessertUpdateRequest;

class DessertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Dessert::class);

        $search = $request->get('search', '');

        $desserts = Dessert::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.desserts.index', compact('desserts', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Dessert::class);

        $menus = Menu::pluck('drink_list', 'id');

        return view('app.desserts.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DessertStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Dessert::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $dessert = Dessert::create($validated);

        return redirect()
            ->route('desserts.edit', $dessert)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Dessert $dessert): View
    {
        $this->authorize('view', $dessert);

        return view('app.desserts.show', compact('dessert'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Dessert $dessert): View
    {
        $this->authorize('update', $dessert);

        $menus = Menu::pluck('drink_list', 'id');

        return view('app.desserts.edit', compact('dessert', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        DessertUpdateRequest $request,
        Dessert $dessert
    ): RedirectResponse {
        $this->authorize('update', $dessert);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($dessert->image) {
                Storage::delete($dessert->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $dessert->update($validated);

        return redirect()
            ->route('desserts.edit', $dessert)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Request $request,
        Dessert $dessert
    ): RedirectResponse {
        $this->authorize('delete', $dessert);

        if ($dessert->image) {
            Storage::delete($dessert->image);
        }

        $dessert->delete();

        return redirect()
            ->route('desserts.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
