<template>
  <div>
    <h1 class="text-3xl font-bold mb-6">{{ getTranslation("employee", "ls_employee") }}</h1>

    <!-- Search Form -->
    <SearchForm :route="'/employee'" :placeholder="getTranslation('employee', 'search_placeholder')"
      @search="handleSearch" />

    <!-- Create Button -->
    <router-link :to="{ name: 'employee.create' }"
      class="mr-4 inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mb-6">
      {{ getTranslation("employee", "create_employee") }}
    </router-link>

    <button @click="toggleArchivedView"
      class="mr-4 bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mb-6">
      {{ showArchived ? getTranslation("country", "view_original") : getTranslation("country", "view_archived") }}
    </button>

    <!-- Table -->
    <Table>
      <TableHeader :columns="columns" :sortColumn="sortColumn" :sortDirection="sortDirection" :route="'/employee'"
        @sort="handleSort" />
      <tbody>
        <TableRow v-for="employee in employees" :key="employee.id" :data="{
          id: employee.id,
          firstname: employee.firstname,
          lastname: employee.lastname,
          email: employee.email,
          username: employee.username,
          country: employee.country.name,
          state: employee.state.name,
          city: employee.city.name,
          income: employee.income,
          date_of_joining: employee.date_of_joining,
          gender: employee.gender,
          created_at: dayjs(employee.created_at).format('DD MMM YYYY, hh:mm A'), // Direct formatting here
          updated_at: dayjs(employee.updated_at).format('DD MMM YYYY, hh:mm A') // Direct formatting here
        }" :editRoute="'/employee/create'" :deleteRoute="'/api/employee'" :deleteFunction="deleteEmployee"
          :restoreFunction="restoreEmployee" :permanentDeleteFunction="permanentDeleteEmployee"
          :showArchived="showArchived" />
      </tbody>
    </Table>

    <!-- Pagination -->
    <div class="mt-6">
      <nav class="flex justify-center space-x-2">
        <button v-for="link in pagination.links" :key="link.label" :disabled="!link.url || link.active" :class="{
          'bg-gray-500 text-white px-4 py-2 rounded': link.active,
          'bg-gray-200 text-black hover:bg-gray-300 px-4 py-2 rounded': !link.active,
          'opacity-50 cursor-not-allowed': !link.url
        }" @click="fetchEmployees(link.url)">
          <span v-html="link.label"></span>
        </button>
      </nav>
    </div>
  </div>
</template>


<script setup>
import { computed, ref, onMounted, watch } from "vue";
import { useEmployeeStore } from "@/stores/employeeStore";
import SearchForm from "@/components/SearchForm.vue";
import Table from "@/components/Table.vue";
import TableHeader from "@/components/TableHeader.vue";
import TableRow from "@/components/TableRow.vue";
import dayjs from "dayjs";
import { useTranslationStore } from "@/stores/translationStore";
import { useRoute } from "vue-router";

// Store instance
const employeeStore = useEmployeeStore();
const translationStore = useTranslationStore();

// Reactive state for toggling archived view
const showArchived = ref(false);

// Reactive properties and computed values
const employees = computed(() => employeeStore.employees);
const pagination = computed(() => employeeStore.pagination);
const sortColumn = computed(() => employeeStore.sortColumn);
const sortDirection = computed(() => employeeStore.sortDirection);

const deleteEmployee = employeeStore.deleteEmployee;
const restoreEmployee = employeeStore.restoreEmployee;

const permanentDeleteEmployee = employeeStore.permanentDeleteEmployee;

const route = useRoute();

// Get translations dynamically
const getTranslation = translationStore.getTranslation;

// Columns configuration
const columns = computed(() => ({
  id: getTranslation("components", "id"),
  firstname: getTranslation("employee", "firstname"),
  lastname: getTranslation("employee", "lastname"),
  email: getTranslation("employee", "email"),
  username: getTranslation("employee", "username"),
  country_id: getTranslation("employee", "country"),
  state_id: getTranslation("employee", "state"),
  city_id: getTranslation("employee", "city"),
  income: getTranslation("employee", "income"),
  date_of_joining: getTranslation("employee", "date_of_joining"),
  gender: getTranslation("employee", "gender"),
  created_at: getTranslation("components", "created_at"),
  updated_at: getTranslation("components", "updated_at"),
}));

// Handle search input
const handleSearch = (query) => {
  employeeStore.fetchEmployees({ search: query });
};

// Fetch employees based on sorting and pagination
const fetchEmployees = (url = null) => {
  employeeStore.fetchEmployees({
    url,
    search: employeeStore.search || "",
    sort: employeeStore.sortColumn || "id",
    direction: employeeStore.sortDirection || "asc",
    archived: showArchived.value,
  });
};

// Handle sorting
const handleSort = async ({ column, direction }) => {
  const newDirection =
    employeeStore.sortColumn === column && employeeStore.sortDirection === "asc"
      ? "desc"
      : "asc";

  employeeStore.setSort(column, newDirection);

  employeeStore.fetchEmployees({
    search: employeeStore.search,
    sort: column,
    direction: newDirection,
    archived: showArchived.value,
  });
};


const toggleArchivedView = () => {
  showArchived.value = !showArchived.value;
  employeeStore.fetchEmployees({
    search: employeeStore.search || "",
    sort: employeeStore.sortColumn || "id",
    direction: employeeStore.sortDirection || "asc",
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

    employeeStore.fetchEmployees({
      search,
      sort,
      direction,
      archived: isArchived,
    });
    
    showArchived.value = isArchived;
  },
  { immediate: true } // Run immediately on component mount
);

// Fetch initial data on component mount
onMounted(() => {
  //employeeStore.fetchEmployees();
});
</script>

<!-- <script>
import { useEmployeeStore } from "@/stores/employeeStore";
import { computed, reactive, onMounted } from "vue";
import SearchForm from "@/components/SearchForm.vue";
import Table from "@/components/Table.vue";
import TableHeader from "@/components/TableHeader.vue";
import TableRow from "@/components/TableRow.vue";
import dayjs from "dayjs";

export default {
  name: "EmployeeIndex",
  components: {
    SearchForm,
    Table,
    TableHeader,
    TableRow,
  },
  setup() {
    const employeeStore = useEmployeeStore();

    const employees = computed(() => employeeStore.employees);
    const pagination = computed(() => employeeStore.pagination);
    const sortColumn = computed(() => employeeStore.sortColumn);
    const sortDirection = computed(() => employeeStore.sortDirection);

    const columns = reactive({
      id: "ID",
      firstname: "First Name",
      lastname: "Last Name",
      email: "Email",
      username: "Username",
      country_id: "Country",
      state_id: "State",
      city_id: "City",
      income: "Income",
      date_of_joining: "Date of Joining",
      gender: "Gender",
      created_at: "Created At",
      updated_at: "Updated At",
    });

    const handleSearch = (query) => {
      employeeStore.fetchEmployees({ search: query });
    };

    const fetchEmployees = (url = null) => {
      employeeStore.fetchEmployees({
        url,
        search: employeeStore.search || "",
        sort: employeeStore.sortColumn || "id",
        direction: employeeStore.sortDirection || "asc",
      });
    };

    const handleSort = async ({ column, direction }) => {
      const newDirection =
        employeeStore.sortColumn === column && employeeStore.sortDirection === "asc"
          ? "desc"
          : "asc";

      employeeStore.setSort(column, newDirection);

      employeeStore.fetchEmployees({
        sort: column,
        direction: newDirection,
      });
    };

    onMounted(() => {
      employeeStore.fetchEmployees();
    });

    return {
      employees,
      pagination,
      columns,
      handleSearch,
      handleSort,
      fetchEmployees,
      sortColumn,
      sortDirection,
      deleteEmployee: employeeStore.deleteEmployee,
      dayjs
    };
  },
};
</script> -->