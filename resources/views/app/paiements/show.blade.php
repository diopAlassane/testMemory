@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('paiements.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.paiements.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.paiements.inputs.price')</h5>
                    <span>{{ $paiement->price ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.paiements.inputs.client_id')</h5>
                    <span>{{ optional($paiement->client)->id ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.paiements.inputs.print_pdf')</h5>
                    <span>{{ $paiement->print_pdf ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('paiements.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Paiement::class)
                <a href="{{ route('paiements.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
