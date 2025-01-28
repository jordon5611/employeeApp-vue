<template>
  <h1 class="text-3xl font-bold mb-6">{{ getTranslation("city", "ls_city") }}</h1>

  <SearchForm :route="'/city'" :placeholder="getTranslation('city', 'search_placeholder')" @search="handleSearch" />

  <router-link :to="{ name: 'city.create' }"
    class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-6">
    {{ getTranslation('city', 'create_city') }}
  </router-link>

  <Table>
    <TableHeader :columns="columns" :sortColumn="sortColumn" :sortDirection="sortDirection" :route="'/city'"
      @sort="handleSort" />
    <tbody>
      <TableRow v-for="city in cities" :key="city.id" :data="{
        id: city.id,
        name: city.name,
        state_name: city.state.name,
        country_name: city.state.country.name,
        created_at: dayjs(city.created_at).format('DD MMM YYYY, hh:mm A'), // Direct formatting here
        updated_at: dayjs(city.updated_at).format('DD MMM YYYY, hh:mm A') // Direct formatting here
      }" :editRoute="'/city/create'" :deleteRoute="'/api/city'" :deleteFunction="deleteCity" />
    </tbody>
  </Table>

  <div class="mt-6">
    <nav class="flex justify-center space-x-2">
      <button v-for="link in pagination.links" :key="link.label" :disabled="!link.url || link.active" :class="{
        'bg-gray-500 text-white px-4 py-2 rounded': link.active,
        'bg-gray-200 text-black hover:bg-gray-300 px-4 py-2 rounded': !link.active,
        'opacity-50 cursor-not-allowed': !link.url
      }" @click="fetchCities(link.url)">
        <span v-html="link.label"></span>
      </button>
    </nav>
  </div>
</template>


<script setup>
import { ref, reactive, computed, onMounted, watch } from "vue";
import SearchForm from "@/components/SearchForm.vue";
import Table from "@/components/Table.vue";
import TableHeader from "@/components/TableHeader.vue";
import TableRow from "@/components/TableRow.vue";
import { useRoute } from "vue-router";
//stores
import { useCityStore } from "@/stores/cityStore";
import { useTranslationStore } from "@/stores/translationStore";
import dayjs from "dayjs";

const route = useRoute(); // Access the current route

// Store setup
const cityStore = useCityStore();
const translationStore = useTranslationStore();

// Reactive and computed variables
const cities = computed(() => cityStore.cities);
const pagination = computed(() => cityStore.pagination);
const sortColumn = computed(() => cityStore.sortColumn);
const sortDirection = computed(() => cityStore.sortDirection);

// Get translations dynamically
const getTranslation = translationStore.getTranslation;

const deleteCity = cityStore.deleteCity;

const columns = computed(() => ({
  id: getTranslation("components", "id"),
  name: translationStore.translations.city?.columns?.city_name || "City Name",
  state_id: translationStore.translations.city?.columns?.state_name || "State Name",
  country_id: translationStore.translations.city?.columns?.country_name || "Country Name",
  created_at: getTranslation("components", "created_at"),
  updated_at: getTranslation("components", "updated_at"),
}));

// Functions
const handleSearch = (query) => {
  cityStore.fetchCities({ search: query });
};

const fetchCities = (url = null) => {
  cityStore.fetchCities({
    url,
    search: cityStore.search || "",
    sort: cityStore.sortColumn || "id",
    direction: cityStore.sortDirection || "asc",
  });
};

const handleSort = ({ column, direction }) => {
  const newDirection =
    cityStore.sortColumn === column && cityStore.sortDirection === "asc"
      ? "desc"
      : "asc";

  cityStore.setSort(column, newDirection);

  cityStore.fetchCities({
    search: cityStore.search,
    sort: column,
    direction: newDirection,
  });
};

// Watch for route query changes
watch(
  () => route.query, // Watch the route's query parameters
  (newQuery) => {
    const { search = "", sort = "id", direction = "asc" } = newQuery;
    cityStore.fetchCities({
      search,
      sort,
      direction,
    });
  },
  { immediate: true } // Run immediately on component mount
);
// Lifecycle hook
onMounted(() => {
  //cityStore.fetchCities();
});
</script>



<!-- <script>
  import { useCityStore } from "@/stores/cityStore";
  import { computed, onMounted, reactive } from "vue";
  import SearchForm from "@/components/SearchForm.vue";
  import Table from "@/components/Table.vue";
  import TableHeader from "@/components/TableHeader.vue";
  import TableRow from "@/components/TableRow.vue";
  import dayjs from "dayjs";
  
  export default {
    name: "CityIndex",
    components: {
      SearchForm,
      Table,
      TableHeader,
      TableRow,
    },
    setup() {
      const cityStore = useCityStore();
  
      const cities = computed(() => cityStore.cities);
      const pagination = computed(() => cityStore.pagination);
      const sortColumn = computed(() => cityStore.sortColumn);
      const sortDirection = computed(() => cityStore.sortDirection);
  
      const columns = reactive({
        id: "ID",
        name: "City Name",
        state_id: "State Name",
        country_id: "Country Name",
        created_at: "Created At",
        updated_at: "Updated At",
      });
  
      const handleSearch = (query) => {
        cityStore.fetchCities({ search: query });
      };
  
      onMounted(() => {
        cityStore.fetchCities();
      });
  
      const fetchCities = (url = null) => {
        cityStore.fetchCities({
          url,
          search: cityStore.search || "",
          sort: cityStore.sortColumn || "id",
          direction: cityStore.sortDirection || "asc",
        });
      };
  
      const handleSort = async ({ column, direction }) => {
        const newDirection =
          cityStore.sortColumn === column &&
          cityStore.sortDirection === "asc"
            ? "desc"
            : "asc";
  
        cityStore.setSort(column, newDirection);
  
        cityStore.fetchCities({
          sort: column,
          direction: newDirection,
        });
      };
  
      return {
        cities,
        pagination,
        columns,
        handleSearch,
        handleSort,
        fetchCities,
        sortColumn,
        sortDirection,
        deleteCity: cityStore.deleteCity,
        dayjs
      };
    },
  };
  </script>
   -->