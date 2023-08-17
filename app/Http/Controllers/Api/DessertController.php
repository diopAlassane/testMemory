<?php

namespace App\Http\Controllers\Api;

use App\Models\Dessert;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\DessertResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DessertCollection;
use App\Http\Requests\DessertStoreRequest;
use App\Http\Requests\DessertUpdateRequest;

class DessertController extends Controller
{
    public function index(Request $request): DessertCollection
    {
        $this->authorize('view-any', Dessert::class);

        $search = $request->get('search', '');

        $desserts = Dessert::search($search)
            ->latest()
            ->paginate();

        return new DessertCollection($desserts);
    }

    public function store(DessertStoreRequest $request): DessertResource
    {
        $this->authorize('create', Dessert::class);

        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $dessert = Dessert::create($validated);

        return new DessertResource($dessert);
    }

    public function show(Request $request, Dessert $dessert): DessertResource
    {
        $this->authorize('view', $dessert);

        return new DessertResource($dessert);
    }

    public function update(
        DessertUpdateRequest $request,
        Dessert $dessert
    ): DessertResource {
        $this->authorize('update', $dessert);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($dessert->image) {
                Storage::delete($dessert->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $dessert->update($validated);

        return new DessertResource($dessert);
    }

    public function destroy(Request $request, Dessert $dessert): Response
    {
        $this->authorize('delete', $dessert);

        if ($dessert->image) {
            Storage::delete($dessert->image);
        }

        $dessert->delete();

        return response()->noContent();
    }
}
