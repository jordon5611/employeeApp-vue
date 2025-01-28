<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">
      {{ isEditMode ? getTranslation("state", "create_state") : getTranslation("state", "edit_state") }}
    </h1>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <!-- State Name -->
      <div>
        <FormInput id="name" name="name" :label="translationStore.translations.state?.columns?.state_name" v-model="form.name" type="text"
          :error="errors.name ? errors.name[0] : ''" />
      </div>

      <!-- Country Dropdown -->
      <div>
        <label for="country_id" class="block text-white font-bold mb-2">
          {{getTranslation('country', 'edit_country')}}:
        </label>
        <select id="country_id" v-model="form.country_id" class="w-half px-4 py-2 text-black rounded-lg">
          <option value="" disabled>Select a country</option>
          <option v-for="country in countries" :key="country.id" :value="country.id">
            {{ country.name }}
          </option>
        </select>
        <p v-if="errors.country_id" class="text-red-500 text-sm mt-1">
          {{ errors.country_id[0] }}
        </p>
      </div>

      <!-- Submit Button -->
      <Button :isEditMode="isEditMode" :createText="'Create'" :updateText="'Update'" />
    </form>
  </div>
</template>


<script setup>
import { computed, onMounted } from "vue";
import { useStateStore } from "@/stores/stateStore";
import { useTranslationStore } from "@/stores/translationStore";
import { useRouter, useRoute } from "vue-router";

const translationStore = useTranslationStore();
// Get translations dynamically
const getTranslation = translationStore.getTranslation;

// Store instance
const stateStore = useStateStore();
const router = useRouter();
const route = useRoute();

// Reactive properties
const isEditMode = computed(() => !!route.params.id);
const form = computed(() => stateStore.form);
const errors = computed(() => stateStore.errors);
const countries = computed(() => stateStore.countries);

// Handle form submission
const handleSubmit = async () => {
  try {
    await translationStore.setLocale(translationStore.locale);
    let response;
    if (isEditMode.value) {
      response = await stateStore.updateState(route.params.id);
    } else {
      response = await stateStore.createState();
    }

    if (response.status === "success") {
      router.push("/state");
    }
  } catch (error) {
    console.error("Submission failed", error);
  }
};

// Fetch initial data on component mount
onMounted(async () => {
  await stateStore.fetchCountries();
  if (isEditMode.value) {
    await stateStore.fetchState(route.params.id);
  } else {
    stateStore.resetForm();
  }
});
</script>


<!-- <script>
import { useStateStore } from "@/stores/stateStore";
import { useRouter, useRoute } from "vue-router";
import { computed, onMounted } from "vue";

export default {
  name: "StateCreate",
  setup() {
    const router = useRouter();
    const route = useRoute();
    const stateStore = useStateStore();

    const isEditMode = computed(() => !!route.params.id);
    const form = computed(() => stateStore.form);
    const errors = computed(() => stateStore.errors);
    const countries = computed(() => stateStore.countries);

    const handleSubmit = async () => {
      try {
        let response;
        if (isEditMode.value) {
          response = await stateStore.updateState(route.params.id);
        } else {
          response = await stateStore.createState();
        }

        if (response.status === "success") {
          router.push("/state");
        }
      } catch (error) {
        console.error("Submission failed", error);
      }
    };

    onMounted(async () => {
      await stateStore.fetchCountries();
      if (isEditMode.value) {
        await stateStore.fetchState(route.params.id);
      } else {
        stateStore.resetForm();
      }
    });

    return {
      form,
      errors,
      countries,
      isEditMode,
      handleSubmit,
    };
  },
};
</script> -->