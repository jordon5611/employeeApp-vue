<template>
    <div>
        <h1 class="text-3xl font-bold mb-6">
            {{ isEditMode ? getTranslation("city", "edit_city") : getTranslation("city", "create_city") }}
        </h1>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <!-- City Name -->
            <div>
                <FormInput id="name" name="name" :label="translationStore.translations.city?.columns?.city_name" v-model="form.name" type="text"
                    :error="errors.name ? errors.name[0] : ''" />
            </div>

            <!-- State Dropdown -->
            <div>
                <label for="state_id" class="block text-white font-bold mb-2">
                    {{translationStore.translations.state?.columns?.state_name}}:
                </label>
                <select id="state_id" v-model="form.state_id" class="w-half px-4 py-2 text-black rounded-lg">
                    <option value="" disabled>Select a state</option>
                    <option v-for="state in states" :key="state.id" :value="state.id">
                        {{ state.name }}
                    </option>
                </select>
                <p v-if="errors.state_id" class="text-red-500 text-sm mt-1">
                    {{ errors.state_id[0] }}
                </p>
            </div>

            <!-- Submit Button -->
            <Button :isEditMode="isEditMode" :createText="'Create'" :updateText="'Update'" />
        </form>
    </div>
</template>

<script setup>
import { useRouter, useRoute } from "vue-router";
import { computed, onMounted } from "vue";
import { useCityStore } from "@/stores/cityStore";
import { useTranslationStore } from "@/stores/translationStore";

const translationStore = useTranslationStore();
// Get translations dynamically
const getTranslation = translationStore.getTranslation;

// Initialize store and router
const router = useRouter();
const route = useRoute();
const cityStore = useCityStore();

// Reactive variables and computed properties
const isEditMode = computed(() => !!route.params.id);
const form = computed(() => cityStore.form);
const errors = computed(() => cityStore.errors);
const states = computed(() => cityStore.states);
const countries = computed(() => cityStore.countries);

// Handle form submission
const handleSubmit = async () => {
  try {
    await translationStore.setLocale(translationStore.locale);
    let response;
    if (isEditMode.value) {
      response = await cityStore.updateCity(route.params.id);
    } else {
      response = await cityStore.createCity();
    }

    if (response.status === "success") {
      router.push("/city");
    }
  } catch (error) {
    console.error("Submission failed", error);
  }
};

// Fetch data when component is mounted
onMounted(async () => {
  await cityStore.fetchCountries();
  await cityStore.fetchStates();

  if (isEditMode.value) {
    await cityStore.fetchCity(route.params.id);
  } else {
    cityStore.resetForm();
  }
});
</script>


<!-- <script>
import { useCityStore } from "@/stores/cityStore";
import { useRouter, useRoute } from "vue-router";
import { computed, onMounted, onUnmounted } from "vue";

export default {
    name: "CityCreate",
    setup() {
        const router = useRouter();
        const route = useRoute();
        const cityStore = useCityStore();

        const isEditMode = computed(() => !!route.params.id);
        const form = computed(() => cityStore.form);
        const errors = computed(() => cityStore.errors);
        const states = computed(() => cityStore.states);
        const countries = computed(() => cityStore.countries);

        const handleSubmit = async () => {
            try {
                let response;
                if (isEditMode.value) {
                    response = await cityStore.updateCity(route.params.id);
                } else {
                    response = await cityStore.createCity();
                }

                if (response.status === "success") {
                    router.push("/city");
                }
            } catch (error) {
                console.error("Submission failed", error);
            }
        };

        onMounted(async () => {
            await cityStore.fetchCountries();
            await cityStore.fetchStates();
            if (isEditMode.value) {
                await cityStore.fetchCity(route.params.id);
            } else {
                cityStore.resetForm();
            }
        });

        // onUnmounted(() => {
        //     countries = []; // Reset countries to an empty array
        // });

        return {
            form,
            errors,
            states,
            countries,
            isEditMode,
            handleSubmit,
        };
    },
};
</script> -->