<x-layout>
    <h1 class="text-3xl font-bold mb-6">@lang('country.ls_country')</h1>

    <x-search-form route="country.index" placeholder="{{ __('country.search_country') }}" />


    <a href="{{ route('country.create') }}"
        class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-6">@lang('country.create_country')</a>

    
    <x-table>
        <x-table-header :columns="['id' => __('components.id'), 'name' => __('country.country_name'), 'created_at' => __('components.created_at'), 'updated_at' => __('components.updated_at')]" :sortColumn="$sortColumn"
            :sortDirection="$sortDirection" route="country.index" />
        <tbody>
            @foreach ($countries as $country)
                <x-table-row :data="['id' => $country->id, 'name' => $country->name, 'created_at' => $country->created_at, 'updated_at' => $country->updated_at]" :editRoute="'country.edit'"
                    :deleteRoute="'country.destroy'"></x-table-row>
            @endforeach
        </tbody>
    </x-table>
    <div class="mt-6">
        {{ $countries->links() }}
    </div>
</x-layout>


@include('partials.sweet-alerts')