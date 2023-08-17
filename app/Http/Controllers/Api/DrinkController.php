<?php

namespace App\Http\Controllers\Api;

use App\Models\Drink;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\DrinkResource;
use App\Http\Resources\DrinkCollection;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DrinkStoreRequest;
use App\Http\Requests\DrinkUpdateRequest;

class DrinkController extends Controller
{
    public function index(Request $request): DrinkCollection
    {
        $this->authorize('view-any', Drink::class);

        $search = $request->get('search', '');

        $drinks = Drink::search($search)
            ->latest()
            ->paginate();

        return new DrinkCollection($drinks);
    }

    public function store(DrinkStoreRequest $request): DrinkResource
    {
        $this->authorize('create', Drink::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $drink = Drink::create($validated);

        return new DrinkResource($drink);
    }

    public function show(Request $request, Drink $drink): DrinkResource
    {
        $this->authorize('view', $drink);

        return new DrinkResource($drink);
    }

    public function update(
        DrinkUpdateRequest $request,
        Drink $drink
    ): DrinkResource {
        $this->authorize('update', $drink);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($drink->image) {
                Storage::delete($drink->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $drink->update($validated);

        return new DrinkResource($drink);
    }

    public function destroy(Request $request, Drink $drink): Response
    {
        $this->authorize('delete', $drink);

        if ($drink->image) {
            Storage::delete($drink->image);
        }

        $drink->delete();

        return response()->noContent();
    }
}
