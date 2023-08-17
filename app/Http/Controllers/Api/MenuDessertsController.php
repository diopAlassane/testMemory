<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DessertResource;
use App\Http\Resources\DessertCollection;

class MenuDessertsController extends Controller
{
    public function index(Request $request, Menu $menu): DessertCollection
    {
        $this->authorize('view', $menu);

        $search = $request->get('search', '');

        $desserts = $menu
            ->desserts()
            ->search($search)
            ->latest()
            ->paginate();

        return new DessertCollection($desserts);
    }

    public function store(Request $request, Menu $menu): DessertResource
    {
        $this->authorize('create', Dessert::class);

        $validated = $request->validate([
            'name' => ['required', 'max:255', 'string'],
            'image' => ['nullable', 'image', 'max:1024'],
            'price' => ['required', 'numeric'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $dessert = $menu->desserts()->create($validated);

        return new DessertResource($dessert);
    }
}
