<template>
  <div>
    <!-- Form Header -->
    <h1 class="text-3xl font-bold mb-6">
      {{ isEditMode ? getTranslation("country", "edit_country") : getTranslation("country", "create_country") }}
    </h1>

    <!-- Form -->
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <!-- Country Name -->
      <div>

        <FormInput id="name" name="name" :label="getTranslation('country', 'edit_country')" v-model="form.name" type="text"
          :error="errors.name ? errors.name[0] : ''" />
        <!-- required="true" -->

      </div>

      <!-- Submit Button -->
      <Button :isEditMode="isEditMode" :createText="getTranslation('components', 'createButton')" :updateText="getTranslation('components', 'updateButton')" />
    </form>
  </div>
</template>

<script setup>
import { useRouter, useRoute } from "vue-router";
import { computed, onMounted } from "vue";
import { useCountryStore } from "@/stores/countryStore";
import { useTranslationStore } from "@/stores/translationStore";
const translationStore = useTranslationStore();

// Get translations dynamically
const getTranslation = translationStore.getTranslation;

// Initialize store and router
const router = useRouter();
const route = useRoute();
const countryStore = useCountryStore();

// Reactive variables and computed properties
const form = countryStore.form; // Bind form directly from the store
const errors = computed(() => countryStore.errors); // Reactive errors from store
const isEditMode = computed(() => !!route.params.id); // Check if it's edit mode

// Fetch country data if in edit mode
onMounted(() => {
  if (isEditMode.value) {
    countryStore.fetchCountry(route.params.id); // Fetch country details for editing
  } else {
    countryStore.resetForm(); // Reset the form for creating
  }
});

// Handle form submission
const handleSubmit = async () => {
  try {
    await translationStore.setLocale(translationStore.locale);
    let response;
    if (isEditMode.value) {
      // Edit mode: update country
      response = await countryStore.updateCountry(route.params.id);
    } else {
      // Create mode: create country
      response = await countryStore.createCountry();
    }

    // Navigate to the country list on success
    if (response.status === "success") {
      router.push("/country");
    }
  } catch (error) {
    console.error("Error submitting form:", error);
  }
};
</script>


