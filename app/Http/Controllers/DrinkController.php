<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Drink;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DrinkStoreRequest;
use App\Http\Requests\DrinkUpdateRequest;

class DrinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Drink::class);

        $search = $request->get('search', '');

        $drinks = Drink::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.drinks.index', compact('drinks', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Drink::class);

        $menus = Menu::pluck('drink_list', 'id');

        return view('app.drinks.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DrinkStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Drink::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $drink = Drink::create($validated);

        return redirect()
            ->route('drinks.edit', $drink)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Drink $drink): View
    {
        $this->authorize('view', $drink);

        return view('app.drinks.show', compact('drink'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Drink $drink): View
    {
        $this->authorize('update', $drink);

        $menus = Menu::pluck('drink_list', 'id');

        return view('app.drinks.edit', compact('drink', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        DrinkUpdateRequest $request,
        Drink $drink
    ): RedirectResponse {
        $this->authorize('update', $drink);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($drink->image) {
                Storage::delete($drink->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $drink->update($validated);

        return redirect()
            ->route('drinks.edit', $drink)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Drink $drink): RedirectResponse
    {
        $this->authorize('delete', $drink);

        if ($drink->image) {
            Storage::delete($drink->image);
        }

        $drink->delete();

        return redirect()
            ->route('drinks.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
