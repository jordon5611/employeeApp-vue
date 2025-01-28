<x-layout>
    <x-form-header routeCreate="state.store" routeUpdate="state.update" titleCreate="{{__('state.create_state')}}"
        titleEdit="{{__('state.edit_state')}}" :item="$state ?? null" />

    <!-- State Name Input -->

    <x-form-input id="name" name="name" label="{{__('state.create_state')}}" :value="$state->name ?? ''" type="text" />

    <x-form-select name="country_id" label="{{__('state.columns.country_name')}}" :options="$countries" :selected="$state->country_id ?? ''"
        placeholder="{{__('state.select_country')}}" required />

    <!-- Submit Button -->
    <x-button :routeName="'state.create'" createText="{{ __('components.createButton') }}" updateText="{{ __('components.updateButton') }}" />
    
    </form>
</x-layout>