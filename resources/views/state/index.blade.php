<x-layout>
    <h1 class="text-3xl font-bold mb-6">@lang('state.ls_state')</h1>

    <!-- Search Bar -->

    <x-search-form route="state.index" placeholder="{{__('state.search_placeholder')}}" />

    <a href="{{ route('state.create') }}"
        class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-6">@lang('state.create_state')</a>


    <x-table>
        <x-table-header :columns="['id' => __('components.id'), 'name' => __('state.columns.state_name'), 'country_id' => __('state.columns.country_name'), 'created_at' => __('components.created_at'), 'updated_at' => __('components.updated_at')]" :sortColumn="$sortColumn" :sortDirection="$sortDirection"
            route="state.index" />
        <tbody>
            @foreach ($states as $state)
                <x-table-row :data="['id' => $state->id, 'name' => $state->name, 'country_id' => $state->country->name, 'created_at' => $state->created_at, 'updated_at' => $state->updated_at]"
                    :editRoute="'state.edit'" :deleteRoute="'state.destroy'"></x-table-row>
            @endforeach
        </tbody>
    </x-table>
    <div class="mt-6">
        {{ $states->links() }}
    </div>
</x-layout>

@include('partials.sweet-alerts')