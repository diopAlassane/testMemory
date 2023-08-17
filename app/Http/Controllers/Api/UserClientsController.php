<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Http\Resources\ClientCollection;

class UserClientsController extends Controller
{
    public function index(Request $request, User $user): ClientCollection
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $clients = $user
            ->clients()
            ->search($search)
            ->latest()
            ->paginate();

        return new ClientCollection($clients);
    }

    public function store(Request $request, User $user): ClientResource
    {
        $this->authorize('create', Client::class);

        $validated = $request->validate([]);

        $client = $user->clients()->create($validated);

        return new ClientResource($client);
    }
}
