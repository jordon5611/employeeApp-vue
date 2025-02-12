<template>
  <h1 class="text-3xl font-bold mb-6">{{ getTranslation("country", "ls_country") }}</h1>

  <SearchForm :route="'/country'" :placeholder="getTranslation('country', 'search_country')" @search="handleSearch" />

  <div class="">
    <router-link to="/country/create"
      class="mr-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-6">
      {{ getTranslation("country", "country_name") }}
    </router-link>

    <button @click="toggleArchivedView"
      class="mr-4 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mb-6">
      {{ showArchived ? getTranslation("country", "view_original") : getTranslation("country", "view_archived") }}
    </button>

  </div>


  <Table>
    <TableHeader :columns="columns" :sortColumn="sortColumn" :sortDirection="sortDirection" :route="'/country'"
      @sort="handleSort" />
    <tbody>
      <TableRow v-for="country in countries" :key="country.id" :data="{
        id: country.id,
        name: country.name,
        created_at: dayjs(country.created_at).format('DD MMM YYYY, hh:mm A'), // Direct formatting here
        updated_at: dayjs(country.updated_at).format('DD MMM YYYY, hh:mm A') // Direct formatting here
      }" :editRoute="'/country/create'" :deleteRoute="'/api/country'" :deleteFunction=deleteCountry
        :restoreFunction="restoreCountry" :permanentDeleteFunction="forceDeleteCountry" :showArchived="showArchived" />
    </tbody>
  </Table>

  <div class="mt-6">
    <nav class="flex justify-center space-x-2">
      <button v-for="link in pagination.links" :key="link.label" :disabled="!link.url || link.active" :class="{
        'bg-gray-500 text-white px-4 py-2 rounded': link.active,
        'bg-gray-200 text-black hover:bg-gray-300 px-4 py-2 rounded': !link.active,
        'opacity-50 cursor-not-allowed': !link.url
      }" @click="fetchCountries(link.url)">
        <span v-html="link.label"></span>
      </button>
    </nav>
  </div>
</template>

<script setup>
import { computed, reactive, onMounted, ref, watch } from "vue";
import { useCountryStore } from "@/stores/countryStore";
import { useTranslationStore } from "@/stores/translationStore";
import SearchForm from "@/components/SearchForm.vue";
import Table from "@/components/Table.vue";
import TableHeader from "@/components/TableHeader.vue";
import TableRow from "@/components/TableRow.vue";
import dayjs from "dayjs";
import { useRoute } from "vue-router";

// Initialize the country store
const countryStore = useCountryStore();
const translationStore = useTranslationStore();

// Reactive state for toggling archived view
const showArchived = ref(false);

const route = useRoute(); // Access the current route

// Reactive variables and computed properties
const countries = computed(() => countryStore.countries); // Country data
const pagination = computed(() => countryStore.pagination); // Pagination metadata
const sortColumn = computed(() => countryStore.sortColumn); // Current sort column
const sortDirection = computed(() => countryStore.sortDirection); // Current sort direction

// Get translations dynamically
const getTranslation = translationStore.getTranslation;

// Table columns (use dynamic translations)
const columns = computed(() => ({
  id: getTranslation("components", "id"),
  name: getTranslation("country", "country_name"),
  created_at: getTranslation("components", "created_at"),
  updated_at: getTranslation("components", "updated_at"),
}));


// Search handler
const handleSearch = (query) => {
  countryStore.fetchCountries({ search: query });
};

// Fetch countries (initial and pagination)
const fetchCountries = (url = null) => {
  countryStore.fetchCountries({
    url,
    search: countryStore.search || "", // Preserve the current search term
    sort: countryStore.sortColumn || "id", // Preserve the current sort column
    direction: countryStore.sortDirection || "asc", // Preserve the current sort direction
    archived: showArchived.value ? true : false, // Fetch archived or active
  });
};

// Sort handler
const handleSort = async ({ column, direction }) => {
  const newDirection =
    countryStore.sortColumn === column &&
      countryStore.sortDirection === "asc"
      ? "desc"
      : "asc";

  // Update sorting state in the store
  countryStore.setSort(column, newDirection);

  // Fetch countries with the updated sort order
  countryStore.fetchCountries({
    search: countryStore.search,
    sort: column,
    direction: newDirection,
    archived: showArchived.value,
  });
};

//Toggle function to switch between archived and active countries
const toggleArchivedView = () => {
  showArchived.value = !showArchived.value;
  countryStore.fetchCountries({
    search: countryStore.search || "",
    sort: countryStore.sortColumn || "id",
    direction: countryStore.sortDirection || "asc",
    archived: showArchived.value,
  });
};

// Watch for route query changes
watch(
  () => route.query, // Watch the route's query parameters
  (newQuery) => {

    const { search = "", sort = "id", direction = "asc", archived = false } = newQuery;

    // Convert `archived` to a boolean
    const isArchived = archived === "true";

    countryStore.fetchCountries({
      search,
      sort,
      direction,
      archived: isArchived,
    });
    
     // Update the `showArchived` ref
     showArchived.value = isArchived;
  },
  { immediate: true } // Run immediately on component mount
);


// Fetch initial data on mount
onMounted(() => {
  const initializeData = async () => {
    if (!Object.keys(translationStore.translations).length) {
      await translationStore.fetchTranslations(translationStore.locale);
    }
    //countryStore.fetchCountries();
  };

  initializeData(); // Call the async function
});

// Expose `deleteCountry` from the store for use in the template
const deleteCountry = countryStore.deleteCountry;

const restoreCountry = countryStore.restoreCountry;

const forceDeleteCountry = countryStore.forceDeleteCountry;
</script>
