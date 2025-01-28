<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        \Log::info('Current locale:', ['locale' => app()->getLocale()]);

        $employeeId = $this->route('employee');
        //$employeeId = $this->route('employee') ? $this->route('employee')->id : null;
        //dd(__('passwords.reset'));
        return [
            'firstname' => ['required', 'string', 'max:255', 'min:3'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:employees,email,' . $employeeId],
            'username' => ['required', 'string', 'max:255', 'unique:employees,username,' . $employeeId],
            'password' => ['nullable', 'string', Password::min(8)->mixedCase()->symbols(), 'confirmed'],
            'dob' => ['required', 'date', 'before:' . now()->subYears(10)->format('Y-m-d')],
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'required|string|digits:10',
            'address' => 'required|string',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
            'income' => 'required|numeric|min:0',
            'date_of_joining' => 'required|date|before_or_equal:today',
        ];
    }

    /**
     * Custom error messages for validation rules.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'firstname' => __('attributes.firstname'),
            'lastname' => __('attributes.lastname'),
            'email' => __('attributes.email'),
            'username' => __('attributes.username'),
            'password' => __('attributes.password'),
            'dob' => __('attributes.dob'),
            'gender' => __('attributes.gender'),
            'phone' => __('attributes.phone'),
            'address' => __('attributes.address'),
            'country_id' => __('attributes.country_id'),
            'state_id' => __('attributes.state_id'),
            'city_id' => __('attributes.city_id'),
            'income' => __('attributes.income'),
            'date_of_joining' => __('attributes.date_of_joining'),
        ];
    }

}
