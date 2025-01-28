<x-layout>
    <x-form-header routeCreate="country.store" routeUpdate="country.update" titleCreate="{{ __('country.create_country') }}"
        titleEdit="{{ __('country.edit_country') }}" :item="$country ?? null" />

    <x-form-input id="name" name="name" label="{{ __('country.country_name') }}" :value="$country->name ?? ''" type="text" />

    <x-button :routeName="'country.create'" createText="{{ __('country.create') }}" updateText="{{ __('country.update') }}" />
    </form>
</x-layout>