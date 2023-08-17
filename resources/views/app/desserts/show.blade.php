@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('desserts.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.desserts.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.desserts.inputs.name')</h5>
                    <span>{{ $dessert->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.desserts.inputs.image')</h5>
                    <x-partials.thumbnail
                        src="{{ $dessert->image ? \Storage::url($dessert->image) : '' }}"
                        size="150"
                    />
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.desserts.inputs.price')</h5>
                    <span>{{ $dessert->price ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.desserts.inputs.menu_id')</h5>
                    <span
                        >{{ optional($dessert->menu)->drink_list ?? '-' }}</span
                    >
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('desserts.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Dessert::class)
                <a href="{{ route('desserts.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
