<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">
      {{ isEditMode ? getTranslation("employee", "edit_employee") : getTranslation("employee", "create_employee") }}
    </h1>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <!-- First Name -->
      <FormInput id="firstname" name="firstname" :label="getTranslation('employee', 'firstname')" v-model="form.firstname" type="text" 
        :error="errors.firstname ? errors.firstname[0] : ''" />

      <!-- Last Name -->
      <FormInput id="lastname" name="lastname" :label="getTranslation('employee', 'lastname')" v-model="form.lastname" type="text" 
        :error="errors.lastname ? errors.lastname[0] : ''" />

      <!-- Email -->
      <FormInput id="email" name="email" :label="getTranslation('employee', 'email')" v-model="form.email" type="email" 
        :error="errors.email ? errors.email[0] : ''" />

      <!-- Username -->
      <FormInput id="username" name="username" :label="getTranslation('employee', 'username')" v-model="form.username" type="text" 
        :error="errors.username ? errors.username[0] : ''" />

      <!-- Password -->
      <FormInput id="password" name="password" :label="getTranslation('employee', 'password')" v-model="form.password" type="password" 
        :error="errors.password ? errors.password[0] : ''" />

      <!-- Confirm Password -->
      <FormInput id="password_confirmation" name="password_confirmation" :label="getTranslation('employee', 'confirmpassword')"
        v-model="form.password_confirmation" type="password" :error="errors.password ? errors.password[0] : ''" />

      <!-- Date of Birth -->
      <FormInput id="dob" name="dob" :label="getTranslation('employee', 'dob')" v-model="form.dob" type="date" 
        :error="errors.dob ? errors.dob[0] : ''" />

      <!-- Country Dropdown -->
      <div class="mb-4">
        <label for="country_id" class="block text-white font-bold mb-2">{{getTranslation('employee', 'country')}}:</label>
        <select id="country_id" v-model="form.country_id" @change="fetchStates"
          class="w-half px-4 py-2 text-black rounded-lg" >
          <option value="" disabled>Select a country</option>
          <option v-for="country in countries" :key="country.id" :value="country.id">
            {{ country.name }}
          </option>
        </select>
        <p v-if="errors.country_id" class="text-red-500 text-sm mt-1">
          {{ errors.country_id[0] }}
        </p>
      </div>

      <!-- State Dropdown -->
      <div class="mb-4">
        <label for="state_id" class="block text-white font-bold mb-2">{{getTranslation('employee', 'state')}}:</label>
        <select id="state_id" v-model="form.state_id" @change="fetchCities"
          class="w-half px-4 py-2 text-black rounded-lg" >
          <option value="" disabled>Select a state</option>
          <option v-for="state in states" :key="state.id" :value="state.id">
            {{ state.name }}
          </option>
        </select>
        <p v-if="errors.state_id" class="text-red-500 text-sm mt-1">
          {{ errors.state_id[0] }}
        </p>
      </div>

      <!-- City Dropdown -->
      <div class="mb-4">
        <label for="city_id" class="block text-white font-bold mb-2">{{ getTranslation('employee', 'city') }}:</label>
        <select id="city_id" v-model="form.city_id" class="w-half px-4 py-2 text-black rounded-lg" >
          <option value="" disabled>Select a city</option>
          <option v-for="city in cities" :key="city.id" :value="city.id">
            {{ city.name }}
          </option>
        </select>
        <p v-if="errors.city_id" class="text-red-500 text-sm mt-1">
          {{ errors.city_id[0] }}
        </p>
      </div>

      <!-- Gender Dropdown -->
      <div class="mb-4">
        <label for="gender" class="block text-white font-bold mb-2">{{ getTranslation('employee', 'gender') }}:</label>
        <select id="gender" v-model="form.gender" class="w-half px-4 py-2 text-black rounded-lg" >
          <option value="" disabled>Select Gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
        <p v-if="errors.gender" class="text-red-500 text-sm mt-1">
          {{ errors.gender[0] }}
        </p>
      </div>



      <!-- Income -->
      <FormInput id="income" name="income" :label="getTranslation('employee', 'income')" v-model="form.income" type="number" 
        :error="errors.income ? errors.income[0] : ''" />

      <!-- Date of Joining -->
      <FormInput id="date_of_joining" name="date_of_joining" :label="getTranslation('employee', 'date_of_joining')" v-model="form.date_of_joining"
         type="date" :error="errors.date_of_joining ? errors.date_of_joining[0] : ''" />



      <!-- Address -->
      <div class="mb-4">
        <label for="address" class="block text-white font-bold mb-2" >{{getTranslation('employee', 'address')}}:</label>
        <textarea id="address" v-model="form.address" class="w-half px-4 py-2 text-black rounded-lg" rows="4"
          placeholder="Enter the address"></textarea>
        <p v-if="errors.address" class="text-red-500 text-sm mt-1">
          {{ errors.address[0] }}
        </p>
      </div>


      <!-- Phone -->
      <FormInput id="phone" name="phone" :label="getTranslation('employee', 'phone')" v-model="form.phone" type="text" 
        :isNumericOnly="true" :error="errors.phone ? errors.phone[0] : ''" />

      <!-- Submit Button -->
      <Button :isEditMode="isEditMode" :createText="'Create'" :updateText="'Update'" />
    </form>
  </div>
</template>

<script setup>
import { computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useEmployeeStore } from "@/stores/employeeStore";
import { useTranslationStore } from "@/stores/translationStore";

const translationStore = useTranslationStore();
// Get translations dynamically
const getTranslation = translationStore.getTranslation;

// Store and router instances
const router = useRouter();
const route = useRoute();
const employeeStore = useEmployeeStore();

// Computed properties for reactive state
const isEditMode = computed(() => !!route.params.id);
const form = computed(() => employeeStore.form);
const errors = computed(() => employeeStore.errors);
const countries = computed(() => employeeStore.countries);
const states = computed(() => employeeStore.states);
const cities = computed(() => employeeStore.cities);

// Handle form submission
const handleSubmit = async () => {
  try {
    await translationStore.setLocale(translationStore.locale);
    let response;
    if (isEditMode.value) {
      console.log(route.params.id);
      response = await employeeStore.updateEmployee(route.params.id);
    } else {
      response = await employeeStore.createEmployee();
    }
    if (response.status === "success") {
      router.push("/employee");
    }
  } catch (error) {
    console.error("Submission failed", error);
  }
};

// Fetch states based on selected country
const fetchStates = async () => {
  form.value.city_id = null;
  employeeStore.cities = [];
  if (!form.value.country_id) {
    employeeStore.states = []; // Clear states if no country is selected
    return;
  }
  await employeeStore.fetchStates(form.value.country_id);
};

// Fetch cities based on selected state
const fetchCities = async () => {
  if (!form.value.state_id) {
    employeeStore.cities = []; // Clear cities if no state is selected
    return;
  }
  await employeeStore.fetchCities(form.value.state_id);
};

// Lifecycle hook for initial data fetching
onMounted(async () => {
  await employeeStore.fetchCountries();
  if (isEditMode.value) {
    await employeeStore.fetchEmployee(route.params.id);
  } else {
    employeeStore.resetForm();
  }
});
</script>

<!-- <script>
import { useEmployeeStore } from "@/stores/employeeStore";
import { computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";

export default {
  name: "EmployeeCreate",
  setup() {
    const router = useRouter();
    const route = useRoute();
    const employeeStore = useEmployeeStore();

    const isEditMode = computed(() => !!route.params.id);
    const form = computed(() => employeeStore.form);
    const errors = computed(() => employeeStore.errors);
    const countries = computed(() => employeeStore.countries);
    const states = computed(() => employeeStore.states);
    const cities = computed(() => employeeStore.cities);

    const handleSubmit = async () => {
      try {
        let response;
        if (isEditMode.value) {
          console.log(route.params.id);
          response = await employeeStore.updateEmployee(route.params.id);
        } else {
          response = await employeeStore.createEmployee();
        }
        if (response.status === "success") {
          router.push("/employee");
        }
      } catch (error) {
        console.error("Submission failed", error);
      }
    };

    const fetchStates = async () => {
      if (!form.value.country_id) {
        employeeStore.states = []; // Clear states if no country selected
        return;
      }
      await employeeStore.fetchStates(form.value.country_id);
    };

    const fetchCities = async () => {
      if (!form.value.state_id) {
        employeeStore.cities = []; // Clear cities if no state selected
        return;
      }
      await employeeStore.fetchCities(form.value.state_id);
    };

    onMounted(async () => {
      await employeeStore.fetchCountries();
      if (isEditMode.value) {
        await employeeStore.fetchEmployee(route.params.id);
      } else {
        employeeStore.resetForm();
      }
    });

    return {
      form,
      errors,
      countries,
      states,
      cities,
      isEditMode,
      handleSubmit,
      fetchStates,
      fetchCities,
    };
  },
};
</script> -->