<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\ReservationCollection;

class ClientReservationsController extends Controller
{
    public function index(
        Request $request,
        Client $client
    ): ReservationCollection {
        $this->authorize('view', $client);

        $search = $request->get('search', '');

        $reservations = $client
            ->reservations()
            ->search($search)
            ->latest()
            ->paginate();

        return new ReservationCollection($reservations);
    }

    public function store(Request $request, Client $client): ReservationResource
    {
        $this->authorize('create', Reservation::class);

        $validated = $request->validate([
            'number_table' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date'],
            'number_place' => ['required', 'max:255', 'string'],
        ]);

        $reservation = $client->reservations()->create($validated);

        return new ReservationResource($reservation);
    }
}
