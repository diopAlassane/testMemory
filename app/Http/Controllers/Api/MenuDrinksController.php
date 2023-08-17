<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DrinkResource;
use App\Http\Resources\DrinkCollection;

class MenuDrinksController extends Controller
{
    public function index(Request $request, Menu $menu): DrinkCollection
    {
        $this->authorize('view', $menu);

        $search = $request->get('search', '');

        $drinks = $menu
            ->drinks()
            ->search($search)
            ->latest()
            ->paginate();

        return new DrinkCollection($drinks);
    }

    public function store(Request $request, Menu $menu): DrinkResource
    {
        $this->authorize('create', Drink::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
            'price' => ['required', 'numeric'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $drink = $menu->drinks()->create($validated);

        return new DrinkResource($drink);
    }
}
