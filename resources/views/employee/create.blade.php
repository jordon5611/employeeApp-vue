<x-layout>
    <x-form-header routeCreate="employee.store" routeUpdate="employee.update" titleCreate="{{__('employee.create_employee')}}"
        titleEdit="{{__('employee.edit_employee')}}" :item="$employee ?? null" />

    <x-form-input id="firstname" name="firstname" label="{{__('employee.firstname')}}" placeholder="John" :value="$employee->firstname ?? ''" type="text" />

    <x-form-input id="lastname" name="lastname" label="{{__('employee.lastname')}}" placeholder="Doe" :value="$employee->lastname ?? ''"
        type="text" />

    <x-form-input id="email" name="email" label="{{__('employee.email')}}" placeholder="abc@example.com" :value="$employee->email ?? ''"
        type="email" />

    <x-form-input id="username" name="username" label="{{__('employee.username')}}" placeholder="abc.123" :value="$employee->username ?? ''" type="text" />

    @if (Request::route()->getName() === 'employee.create')

        <x-form-input id="password" name="password" label="{{__('employee.password')}}" type="password" />

        <x-form-input id="password_confirmation" name="password_confirmation" label="{{__('employee.confirmpassword')}}" type="password" />

    @endif

    <x-form-input id="dob" name="dob" label="{{__('employee.dob')}}" :value="$employee->dob ?? ''" type="date" />

    <x-form-select name="gender" label="{{__('employee.gender')}}" :options="['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other']"
        :selected="$employee->gender ?? ''"  />


    <x-form-input id="phone" name="phone" label="{{__('employee.phone')}}" placeholder="123-456-7890" :value="$employee->phone ?? ''"
        type="text" maxlength="11" pattern="\d{3}-\d{3}-\d{4}"
        oninput="this.value = this.value.replace(/[^0-9-]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');" />


    <div class="mb-4">
        <label for="address" class="block text-white font-bold mb-2 rounded-lg">@lang('employee.address'):</label>
        <textarea id="address" name="address" class="w-half px-4 py-2 text-black rounded-lg" rows="4"
            >{{ old('address', $employee->address ?? '') }}</textarea>
        @error('address')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>


    <!-- Country -->

    <x-form-select name="country_id" label="{{__('employee.country')}}" :options="$countries" :selected="$employee->country_id ?? ''"
        placeholder="Select a country"  />

    <!-- State -->
    <x-form-select name="state_id" label="{{__('employee.state')}}" :options="$states ?? []" :selected="$employee->state_id ?? ''"
        placeholder="Select a state"  />

    <!-- City -->
    <x-form-select name="city_id" label="{{__('employee.city')}}" :options="$cities ?? []" :selected="$employee->city_id ?? ''"
        placeholder="Select a city"  />

    <x-form-input id="income" name="income" label="{{__('employee.income')}}" placeholder="60000" :value="$employee->income ?? ''"
        type="number" />

    <x-form-input id="date_of_joining" name="date_of_joining" label="{{__('employee.date_of_joining')}}" :value="$employee->date_of_joining ?? ''" type="date" />

    <x-button :routeName="'employee.create'" createText="{{__('components.createButton')}}" updateText="{{__('components.updateButton')}}" />

    </form>
</x-layout>


<script>
    document.getElementById('country_id').addEventListener('change', function () {
        const countryId = this.value;
        fetch(`/states/${countryId}`)
            .then(response => response.json())
            .then(states => {
                const stateSelect = document.getElementById('state_id');
                stateSelect.innerHTML = '<option value="">Select a state</option>';
                states.forEach(state => {
                    const option = document.createElement('option');
                    option.value = state.id;
                    option.textContent = state.name;
                    stateSelect.appendChild(option);
                });
                document.getElementById('city_id').innerHTML = '<option value="">Select a city</option>';
            });
    });

    document.getElementById('state_id').addEventListener('change', function () {
        const stateId = this.value;
        fetch(`/cities/${stateId}`)
            .then(response => response.json())
            .then(cities => {
                const citySelect = document.getElementById('city_id');
                citySelect.innerHTML = '<option value="">Select a city</option>';
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
            });
    });
</script>