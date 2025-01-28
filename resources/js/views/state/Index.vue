<template>
  <h1 class="text-3xl font-bold mb-6">{{getTranslation('state', 'ls_state')}}</h1>

  <SearchForm :route="'/state'" :placeholder="getTranslation('state', 'search_placeholder')" @search="handleSearch" />

  <router-link :to="{ name: 'state.create' }"
    class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-6">
    {{getTranslation('state', 'create_state')}}
  </router-link>

  <Table>
    <TableHeader :columns="columns" :sortColumn="sortColumn" :sortDirection="sortDirection" :route="'/state'"
      @sort="handleSort" />
    <tbody>
      <TableRow v-for="state in states" :key="state.id" :data="{
        id: state.id,
        name: state.name,
        country_name: state.country.name,
        created_at: dayjs(state.created_at).format('DD MMM YYYY, hh:mm A'), // Direct formatting here
        updated_at: dayjs(state.updated_at).format('DD MMM YYYY, hh:mm A') // Direct formatting here
      }" :editRoute="'/state/create'" :deleteRoute="'/api/state'" :deleteFunction="deleteState" />
    </tbody>
  </Table>

  <div class="mt-6">
    <nav class="flex justify-center space-x-2">
      <button v-for="link in pagination.links" :key="link.label" :disabled="!link.url || link.active" :class="{
        'bg-gray-500 text-white px-4 py-2 rounded': link.active,
        'bg-gray-200 text-black hover:bg-gray-300 px-4 py-2 rounded': !link.active,
        'opacity-50 cursor-not-allowed': !link.url
      }" @click="fetchStates(link.url)">
        <span v-html="link.label"></span>
      </button>
    </nav>
  </div>
</template>


<script setup>
import { computed, onMounted, reactive, watch } from "vue";
import { useStateStore } from "@/stores/stateStore";
import dayjs from "dayjs";
import SearchForm from "@/components/SearchForm.vue";
import Table from "@/components/Table.vue";
import TableHeader from "@/components/TableHeader.vue";
import TableRow from "@/components/TableRow.vue";
import { useTranslationStore } from "@/stores/translationStore";
import { useRoute } from "vue-router";

const translationStore = useTranslationStore();

const route = useRoute(); // Access the current route

// Get translations dynamically
const getTranslation = translationStore.getTranslation;

// Store instance
const stateStore = useStateStore();

// Reactive properties
const states = computed(() => stateStore.states);
const pagination = computed(() => stateStore.pagination);
const sortColumn = computed(() => stateStore.sortColumn);
const sortDirection = computed(() => stateStore.sortDirection);

const deleteState = stateStore.deleteState;

// Columns definition
const columns = computed(() => ({
  id: getTranslation("components", "id"),
  name: translationStore.translations.state?.columns?.state_name || "State Name",
  country_id: translationStore.translations.state?.columns?.country_name || "Country Name",
  created_at: getTranslation("components", "created_at"),
  updated_at: getTranslation("components", "updated_at"),
}));

// Methods
const handleSearch = (query) => {
  stateStore.fetchStates({ search: query });
};

const fetchStates = (url = null) => {
  stateStore.fetchStates({
    url,
    search: stateStore.search || "",
    sort: stateStore.sortColumn || "id",
    direction: stateStore.sortDirection || "asc",
  });
};

const handleSort = async ({ column, direction }) => {
  const newDirection =
    stateStore.sortColumn === column && stateStore.sortDirection === "asc"
      ? "desc"
      : "asc";

  stateStore.setSort(column, newDirection);

  stateStore.fetchStates({
    search: stateStore.search,
    sort: column,
    direction: newDirection,
  });
};

// Watch for route query changes
watch(
  () => route.query, // Watch the route's query parameters
  (newQuery) => {
    
    const { search = "", sort = "id", direction = "asc" } = newQuery;
    stateStore.fetchStates({
      search,
      sort,
      direction,
    });
  },
  { immediate: true } // Run immediately on component mount
);

// Fetch initial data on component mount
onMounted(() => {
  
  //stateStore.fetchStates();
});
</script>

<!-- <script>
import { useStateStore } from "@/stores/stateStore";
import { computed, onMounted, reactive } from "vue";
import dayjs from "dayjs";
import SearchForm from "@/components/SearchForm.vue";
import Table from "@/components/Table.vue";
import TableHeader from "@/components/TableHeader.vue";
import TableRow from "@/components/TableRow.vue";

export default {
  name: "StateIndex",
  components: {
    SearchForm,
    Table,
    TableHeader,
    TableRow,
  },
  setup() {
    const stateStore = useStateStore();

    const states = computed(() => stateStore.states);
    const pagination = computed(() => stateStore.pagination);
    const sortColumn = computed(() => stateStore.sortColumn);
    const sortDirection = computed(() => stateStore.sortDirection);

    const columns = reactive({
      id: "ID",
      name: "State Name",
      country_id: "Country Name",
      created_at: "Created At",
      updated_at: "Updated At",
    });

    const handleSearch = (query) => {
      stateStore.fetchStates({ search: query });
    };

    onMounted(() => {
      stateStore.fetchStates();
    });

    const fetchStates = (url = null) => {
      stateStore.fetchStates({
        url,
        search: stateStore.search || "",
        sort: stateStore.sortColumn || "id",
        direction: stateStore.sortDirection || "asc",
      });
    };

    const handleSort = async ({ column, direction }) => {
      const newDirection =
        stateStore.sortColumn === column &&
          stateStore.sortDirection === "asc"
          ? "desc"
          : "asc";

      stateStore.setSort(column, newDirection);

      stateStore.fetchStates({
        sort: column,
        direction: newDirection,
      });
    };

    return {
      states,
      pagination,
      columns,
      handleSearch,
      handleSort,
      fetchStates,
      sortColumn,
      sortDirection,
      deleteState: stateStore.deleteState,
      dayjs
    };
  },
};
</script> -->