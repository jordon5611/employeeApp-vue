<x-layout>
    <x-form-header routeCreate="city.store" routeUpdate="city.update" titleCreate="{{__('city.create_city')}}" titleEdit="{{__('city.edit_city')}}"
        :item="$city ?? null" />

    <x-form-input id="name" name="name" label="{{__('city.columns.city_name')}}" :value="$city->name ?? ''" type="text" />


    <!-- State -->
    <x-form-select name="state_id" label="{{__('city.columns.state_name')}}" :options="$states ?? []" :selected="$city->state_id ?? ''"
        placeholder="{{__('city.select_state')}}" required />


    <x-button :routeName="'city.create'" createText="{{ __('components.createButton') }}" updateText="{{ __('components.updateButton') }}" />
    </form>
</x-layout>