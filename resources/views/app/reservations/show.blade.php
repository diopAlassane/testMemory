@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('reservations.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.reservations.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.reservations.inputs.number_table')</h5>
                    <span>{{ $reservation->number_table ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.reservations.inputs.date')</h5>
                    <span>{{ $reservation->date ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.reservations.inputs.time')</h5>
                    <span>{{ $reservation->time ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.reservations.inputs.number_place')</h5>
                    <span>{{ $reservation->number_place ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.reservations.inputs.client_id')</h5>
                    <span>{{ optional($reservation->client)->id ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a
                    href="{{ route('reservations.index') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Reservation::class)
                <a
                    href="{{ route('reservations.create') }}"
                    class="btn btn-light"
                >
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
