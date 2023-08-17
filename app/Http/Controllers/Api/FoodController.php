<?php

namespace App\Http\Controllers\Api;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\FoodResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\FoodCollection;
use App\Http\Requests\FoodStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FoodUpdateRequest;

class FoodController extends Controller
{
    public function index(Request $request): FoodCollection
    {
        $this->authorize('view-any', Food::class);

        $search = $request->get('search', '');

        $allFood = Food::search($search)
            ->latest()
            ->paginate();

        return new FoodCollection($allFood);
    }

    public function store(FoodStoreRequest $request): FoodResource
    {
        $this->authorize('create', Food::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $food = Food::create($validated);

        return new FoodResource($food);
    }

    public function show(Request $request, Food $food): FoodResource
    {
        $this->authorize('view', $food);

        return new FoodResource($food);
    }

    public function update(FoodUpdateRequest $request, Food $food): FoodResource
    {
        $this->authorize('update', $food);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($food->image) {
                Storage::delete($food->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $food->update($validated);

        return new FoodResource($food);
    }

    public function destroy(Request $request, Food $food): Response
    {
        $this->authorize('delete', $food);

        if ($food->image) {
            Storage::delete($food->image);
        }

        $food->delete();

        return response()->noContent();
    }
}
