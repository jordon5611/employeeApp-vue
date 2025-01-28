import { defineStore } from "pinia";
import axios from "axios";

export const useEmployeeStore = defineStore("employee", {
  state: () => ({
    employees: [],
    countries: [],
    states: [],
    cities: [],
    sortColumn: "id",
    sortDirection: "asc",
    search: "",
    pagination: {},
    form: {
      firstname: "",
      lastname: "",
      email: "",
      username: "",
      password: "",
      password_confirmation: "",
      dob: "",
      country_id: null,
      state_id: null,
      city_id: null,
      income: "",
      date_of_joining: "",
      gender: "",
      address: "",
      phone: "",
    },
    errors: {},
  }),
  actions: {
    async fetchEmployees({ search = "", sort = "id", direction = "asc", url = "/api/employee" } = {}) {
      try {
        const urlObj = new URL(url, window.location.origin); // Ensure URL is absolute
        const params = new URLSearchParams(urlObj.search);

        // Update query parameters (override or add new ones)
        params.set("search", search);
        params.set("sort", sort);
        params.set("direction", direction);

        // Update the browser's URL without reloading the page
        const webUrl = new URL(window.location.href);
        webUrl.search = params.toString(); // Update query parameters in the browser's URL
        window.history.replaceState({}, '', webUrl);

        const response = await axios.get(url, {
          params: { search, sort, direction },
        });
        this.employees = response.data.data;
        this.pagination = response.data;

        // Save the state of search, sort, and direction
        this.search = search;
        this.sortColumn = sort;
        this.sortDirection = direction;
      } catch (error) {
        console.error("Error fetching employees:", error);
      }
    },
    async fetchCountries() {
      try {
        const response = await axios.get("/api/countries/all");
        this.countries = response.data;
      } catch (error) {
        console.error("Error fetching countries:", error);
      }
    },
    async fetchStates(countryId) {
      try {
        if (!countryId) {
          this.states = []; // Clear states if no country ID provided
          return;
        }
        const response = await axios.get(`/states/${countryId}`);
        this.states = response.data; // Populate states based on country ID
      } catch (error) {
        console.error("Error fetching states:", error);
        this.states = []; // Clear states if an error occurs
      }
    },

    async fetchCities(stateId) {
      try {
        if (!stateId) {
          this.cities = []; // Clear cities if no state ID provided
          return;
        }
        const response = await axios.get(`/cities/${stateId}`);
        this.cities = response.data; // Populate cities based on state ID
      } catch (error) {
        console.error("Error fetching cities:", error);
        this.cities = []; // Clear cities if an error occurs
      }
    },

    async fetchEmployee(id) {
      try {
        const response = await axios.get(`/api/employee/${id}`);
        const { country_id, password, ...data } = response.data;
        Object.assign(this.form, data);


      } catch (error) {
        console.error("Error fetching employee:", error);
      }
    },
    async createEmployee() {
      try {
        const response = await axios.post("/api/employee", this.form, {
          headers: { "Accept-Language": this.locale },
        });
        this.resetForm();
        return response.data;
      } catch (error) {
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
        throw error;
      }
    },
    async updateEmployee(id) {
      try {
        const response = await axios.put(`/api/employee/${id}`, this.form);
        this.resetForm();
        return response.data;
      } catch (error) {
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
        throw error;
      }
    },
    async deleteEmployee(id) {
      try {
        const response = await axios.delete(`/api/employee/${id}`);
        this.employees = this.employees.filter((employee) => employee.id !== id);
        return { status: "success", message: response.data.message };
      } catch (error) {
        console.error("Error deleting employee:", error);
        return { status: "error", message: "Failed to delete the employee." };
      }
    },
    setSort(column, direction) {
      this.sortColumn = column;
      this.sortDirection = direction;
    },
    resetForm() {
      this.form = {
        firstname: "",
        lastname: "",
        email: "",
        username: "",
        password: "",
        dob: "",
        country_id: null,
        state_id: null,
        city_id: null,
        income: "",
        date_of_joining: "",
        gender: "",
        address: "",
        phone: "",
      };
      this.errors = {};
    },
  },
});
