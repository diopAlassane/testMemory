<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Menu;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\FoodStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FoodUpdateRequest;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Food::class);

        $search = $request->get('search', '');

        $allFood = Food::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.all_food.index', compact('allFood', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Food::class);

        $menus = Menu::pluck('drink_list', 'id');

        return view('app.all_food.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FoodStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Food::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $food = Food::create($validated);

        return redirect()
            ->route('all-food.edit', $food)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Food $food): View
    {
        $this->authorize('view', $food);

        return view('app.all_food.show', compact('food'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Food $food): View
    {
        $this->authorize('update', $food);

        $menus = Menu::pluck('drink_list', 'id');

        return view('app.all_food.edit', compact('food', 'menus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        FoodUpdateRequest $request,
        Food $food
    ): RedirectResponse {
        $this->authorize('update', $food);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            if ($food->image) {
                Storage::delete($food->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $food->update($validated);

        return redirect()
            ->route('all-food.edit', $food)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Food $food): RedirectResponse
    {
        $this->authorize('delete', $food);

        if ($food->image) {
            Storage::delete($food->image);
        }

        $food->delete();

        return redirect()
            ->route('all-food.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
