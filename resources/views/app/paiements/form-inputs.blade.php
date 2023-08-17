@php $editing = isset($paiement) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.number
            name="price"
            label="Price"
            :value="old('price', ($editing ? $paiement->price : ''))"
            max="255"
            step="0.01"
            placeholder="Price"
            required
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="client_id" label="Client" required>
            @php $selected = old('client_id', ($editing ? $paiement->client_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Client</option>
            @foreach($clients as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.text
            name="print_pdf"
            label="Print Pdf"
            :value="old('print_pdf', ($editing ? $paiement->print_pdf : ''))"
            maxlength="255"
            placeholder="Print Pdf"
            required
        ></x-inputs.text>
    </x-inputs.group>
</div>
