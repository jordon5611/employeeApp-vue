<x-layout>
    <h1 class="text-3xl font-bold mb-6">@lang('city.ls_city')</h1>

    <!-- Search Bar -->

    <x-search-form route="city.index" placeholder="{{__('city.search_placeholder')}}" />

    <a href="{{ route('city.create') }}"
        class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-6">@lang('city.create_city')</a>

    <x-table>
        <x-table-header :columns="['id' => __('components.id'), 'name' =>  __('city.columns.city_name'), 'state_id' => __('city.columns.state_name'), 'country_id' =>  __('city.columns.country_name'), 'created_at' => __('components.created_at'), 'updated_at' => __('components.updated_at')]" :sortColumn="$sortColumn"
            :sortDirection="$sortDirection" route="city.index" />
        <tbody>
            @foreach ($cities as $city)
                <x-table-row :data="['id' => $city->id, 'name' => $city->name, 'state_id' => $city->state->name, 'country_id' => $city->state->country->name, 'created_at' => $city->created_at, 'updated_at' => $city->updated_at]"
                    :editRoute="'city.edit'" :deleteRoute="'city.destroy'"></x-table-row>
            @endforeach
        </tbody>
    </x-table>

    <div class="mt-6">
        {{ $cities->links() }}
    </div>
</x-layout>

@include('partials.sweet-alerts')