import { defineStore } from "pinia";
import axios from "axios";

export const useCityStore = defineStore("city", {
  state: () => ({
    cities: [],
    states: [], // For the dropdown in the form
    countries: [], // For the dropdown in the form
    sortColumn: "id",
    sortDirection: "asc",
    search: "",
    pagination: {}, // Metadata for pagination
    form: {
      name: "",
      state_id: null,
      country_id: null,
    },
    errors: {},
  }),
  actions: {
    async fetchCities({ search = "", sort = "id", direction = "asc", url = "/api/city" } = {}) {
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
        this.cities = response.data.data;
        this.pagination = response.data;

        // Save the state of search, sort, and direction
        this.search = search;
        this.sortColumn = sort;
        this.sortDirection = direction;
      } catch (error) {
        console.error("Error fetching cities:", error);
      }
    },
    async fetchStates() {
      try {
        const response = await axios.get("/api/states/all");
        this.states = response.data;
      } catch (error) {
        console.error("Error fetching states:", error);
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
    async fetchCity(id) {
      try {
        const response = await axios.get(`/api/city/${id}`);
        this.form.name = response.data.name;
        this.form.state_id = response.data.state_id;
        this.form.country_id = response.data.country_id;
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.errors = error.response.data.errors;
        }
        console.error("Error fetching city:", error);
      }
    },
    async createCity() {
      try {
        const response = await axios.post("/api/city", this.form);
        if (response.data.status === "success") {
          this.resetForm();
        }
        return response.data;
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.errors = error.response.data.errors;
        }
        throw error;
      }
    },
    async updateCity(id) {
      try {
        const response = await axios.put(`/api/city/${id}`, this.form);
        if (response.data.status === "success") {
          this.resetForm();
        }
        return response.data;
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.errors = error.response.data.errors;
        }
        throw error;
      }
    },
    async deleteCity(id) {
      try {
        // Send DELETE request to the backend
        const response = await axios.delete(`/api/city/${id}`);

        // Update the cities array to reflect the deleted city
        this.cities = this.cities.filter(city => city.id !== id);

        // Return a success message
        return { status: "success", message: response.data.message };
      } catch (error) {
        const errorMessage =
          error.response?.data?.message || "Failed to delete the city.";

        // Log the error
        console.error("Error deleting city:", error);
        // Return the error message
        return { status: "error", message: errorMessage };
      }
    },

    setSort(column, direction) {
      this.sortColumn = column;
      this.sortDirection = direction;
    },
    resetForm() {
      this.form.name = "";
      this.form.state_id = null;
      this.form.country_id = null;
      this.errors = {};
    },
  },
});
