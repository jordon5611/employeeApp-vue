<x-layout>
    <h1 class="text-3xl font-bold mb-6">@lang('employee.ls_employee')</h1>

    <!-- Search Bar -->
    <x-search-form route="employee.index" placeholder="{{__('employee.search_placeholder')}}" />

    <a href="{{ route('employee.create') }}"
        class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-6">
        @lang('employee.create_employee')
    </a>

    <x-table>
        <x-table-header :columns="[
        'id' => __('components.id'),
        'firstname' => __('employee.name'),
        'email' =>__('employee.email'),
        'username' => __('employee.username'),
        'dob' =>__('employee.dob'),
        'gender' => __('employee.gender'),
        'phone' => __('employee.phone'),
        'address' => __('employee.address'),
        'date_of_joining' => __('employee.date_of_joining'),
        'income' => __('employee.income'),
        'country' => __('employee.country'),
        'state' => __('employee.state'),
        'city' => __('employee.city'),
        'created_at' => __('components.created_at'),
        'updated_at' => __('components.updated_at')
    ]" :sortColumn="$sortColumn"
            :sortDirection="$sortDirection" route="employee.index" />
        <tbody>
            @foreach ($employees as $employee)
                    <x-table-row :data="[
                    'id' => $employee->id,
                    'firstname' => $employee->firstname . ' ' . $employee->lastname,
                    'email' => $employee->email,
                    'username' => $employee->username,
                    'dob' => $employee->dob,
                    'gender' => $employee->gender,
                    'phone' => $employee->phone,
                    'address' => $employee->address,
                    'date_of_joining' => $employee->date_of_joining,
                    'income' => '$' . number_format($employee->income, 2),
                    'country' => $employee->country->name,
                    'state' => $employee->state->name,
                    'city' => $employee->city->name,
                    'created_at' => $employee->created_at,
                    'updated_at' => $employee->updated_at
                ]" :editRoute="'employee.edit'" :deleteRoute="'employee.destroy'">
                    </x-table-row>
            @endforeach
        </tbody>
    </x-table>
    <div class="mt-6">
        {{ $employees->links() }}
    </div>
</x-layout>
