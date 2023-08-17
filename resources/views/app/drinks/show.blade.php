@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('drinks.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.drinks.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.drinks.inputs.name')</h5>
                    <span>{{ $drink->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.drinks.inputs.image')</h5>
                    <x-partials.thumbnail
                        src="{{ $drink->image ? \Storage::url($drink->image) : '' }}"
                        size="150"
                    />
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.drinks.inputs.price')</h5>
                    <span>{{ $drink->price ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.drinks.inputs.menu_id')</h5>
                    <span>{{ optional($drink->menu)->drink_list ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('drinks.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Drink::class)
                <a href="{{ route('drinks.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
