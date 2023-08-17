<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Resources\MenuResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuCollection;

class ClientMenusController extends Controller
{
    public function index(Request $request, Client $client): MenuCollection
    {
        $this->authorize('view', $client);

        $search = $request->get('search', '');

        $menus = $client
            ->menus()
            ->search($search)
            ->latest()
            ->paginate();

        return new MenuCollection($menus);
    }

    public function store(Request $request, Client $client): MenuResource
    {
        $this->authorize('create', Menu::class);

        $validated = $request->validate([
            'drink_list' => ['required', 'max:255', 'string'],
            'dessert_list' => ['required', 'max:255', 'string'],
            'food_list' => ['required', 'max:255', 'string'],
        ]);

        $menu = $client->menus()->create($validated);

        return new MenuResource($menu);
    }
}
