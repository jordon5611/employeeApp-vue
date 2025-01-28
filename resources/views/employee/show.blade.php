<x-layout>
    <h1 class="text-3xl font-bold mb-6">Employee Details</h1>

    <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold text-white mb-4">Personal Information</h2>
        <p><strong>Name:</strong> {{ $employee->firstname }} {{ $employee->lastname }}
        </p>
        <p><strong>Date of Birth:</strong> {{ $employee->dob }}</p>
        <p><strong>Gender:</strong> {{ $employee->gender }}</p>
        <p><strong>Phone:</strong> {{ $employee->phone }}</p>
        <p><strong>Address:</strong> {{ $employee->address }}</p>

        <h2 class="text-xl font-bold text-white mt-6 mb-4">Employment Information</h2>
        <p><strong>Date of Joining:</strong> {{ $employee->date_of_joining }}</p>
        <p><strong>Income:</strong> ${{ number_format($employee->income, 2) }}</p>

        <h2 class="text-xl font-bold text-white mt-6 mb-4">Location</h2>
        <p><strong>Country:</strong> {{ $employee->country->name }}</p>
        <p><strong>State:</strong> {{ $employee->state->name }}</p>
        <p><strong>City:</strong> {{ $employee->city->name }}</p>


        <a href="{{ route('employee.index') }}"
            class="block mt-6 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Back to Employee List</a>

    </div>
</x-layout>