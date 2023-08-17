@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('commandes.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.commandes.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.commandes.inputs.date')</h5>
                    <span>{{ $commande->date ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.commandes.inputs.time')</h5>
                    <span>{{ $commande->time ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.commandes.inputs.drink')</h5>
                    <span>{{ $commande->drink ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.commandes.inputs.dessert')</h5>
                    <span>{{ $commande->dessert ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.commandes.inputs.food')</h5>
                    <span>{{ $commande->food ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.commandes.inputs.client_id')</h5>
                    <span>{{ optional($commande->client)->id ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('commandes.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Commande::class)
                <a href="{{ route('commandes.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
