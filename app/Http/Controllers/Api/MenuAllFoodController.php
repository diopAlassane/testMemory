<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Resources\FoodResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodCollection;

class MenuAllFoodController extends Controller
{
    public function index(Request $request, Menu $menu): FoodCollection
    {
        $this->authorize('view', $menu);

        $search = $request->get('search', '');

        $allFood = $menu
            ->allFood()
            ->search($search)
            ->latest()
            ->paginate();

        return new FoodCollection($allFood);
    }

    public function store(Request $request, Menu $menu): FoodResource
    {
        $this->authorize('create', Food::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
            'price' => ['required', 'numeric'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $food = $menu->allFood()->create($validated);

        return new FoodResource($food);
    }
}
