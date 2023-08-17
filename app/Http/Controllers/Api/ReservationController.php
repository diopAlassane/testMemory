<?php

namespace App\Http\Controllers\Api;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Http\Resources\ReservationCollection;
use App\Http\Requests\ReservationStoreRequest;
use App\Http\Requests\ReservationUpdateRequest;

class ReservationController extends Controller
{
    public function index(Request $request): ReservationCollection
    {
        $this->authorize('view-any', Reservation::class);

        $search = $request->get('search', '');

        $reservations = Reservation::search($search)
            ->latest()
            ->paginate();

        return new ReservationCollection($reservations);
    }

    public function store(ReservationStoreRequest $request): ReservationResource
    {
        $this->authorize('create', Reservation::class);

        $validated = $request->validated();

        $reservation = Reservation::create($validated);

        return new ReservationResource($reservation);
    }

    public function show(
        Request $request,
        Reservation $reservation
    ): ReservationResource {
        $this->authorize('view', $reservation);

        return new ReservationResource($reservation);
    }

    public function update(
        ReservationUpdateRequest $request,
        Reservation $reservation
    ): ReservationResource {
        $this->authorize('update', $reservation);

        $validated = $request->validated();

        $reservation->update($validated);

        return new ReservationResource($reservation);
    }

    public function destroy(
        Request $request,
        Reservation $reservation
    ): Response {
        $this->authorize('delete', $reservation);

        $reservation->delete();

        return response()->noContent();
    }
}
