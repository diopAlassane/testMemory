@php $editing = isset($menu) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="drink_list"
            label="Drink List"
            :value="old('drink_list', ($editing ? $menu->drink_list : ''))"
            maxlength="255"
            placeholder="Drink List"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="dessert_list"
            label="Dessert List"
            :value="old('dessert_list', ($editing ? $menu->dessert_list : ''))"
            maxlength="255"
            placeholder="Dessert List"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="food_list"
            label="Food List"
            :value="old('food_list', ($editing ? $menu->food_list : ''))"
            maxlength="255"
            placeholder="Food List"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="client_id" label="Client" required>
            @php $selected = old('client_id', ($editing ? $menu->client_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Client</option>
            @foreach($clients as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
