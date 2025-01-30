import { defineStore } from "pinia";
import axios from "axios";

export const useStateStore = defineStore("state", {
    state: () => ({
        states: [],
        countries: [], // For the dropdown in the form
        sortColumn: "id",
        sortDirection: "asc",
        search: "",
        pagination: {}, // Metadata for pagination
        form: {
            name: "",
            country_id: null,
        },
        errors: {},
    }),
    actions: {
        async fetchStates({ search = "", sort = "id", direction = "asc", archived = false, url = "/api/state" } = {}) {
            try {
                // Extract existing query parameters from the provided URL
                const urlObj = new URL(url, window.location.origin); // Ensure URL is absolute
                const params = new URLSearchParams(urlObj.search);

                // Update query parameters (override or add new ones)
                params.set("search", search);
                params.set("sort", sort);
                params.set("direction", direction);

                params.set("archived", archived);

                // Update the browser's URL without reloading the page
                const webUrl = new URL(window.location.href);
                webUrl.search = params.toString(); // Update query parameters in the browser's URL
                window.history.replaceState({}, '', webUrl);

                const response = await axios.get(urlObj.pathname, {
                    params,
                });
                this.states = response.data.data;
                this.pagination = response.data;

                // Save the state of search, sort, and direction
                this.search = search;
                this.sortColumn = sort;
                this.sortDirection = direction;
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
        async fetchState(id) {
            try {
                const response = await axios.get(`/api/state/${id}`);
                this.form.name = response.data.name;
                this.form.country_id = response.data.country_id;
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                }
                console.error("Error fetching state:", error);
            }
        },
        async createState() {
            try {
                const response = await axios.post("/api/state", this.form);
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
        async updateState(id) {
            try {
                const response = await axios.put(`/api/state/${id}`, this.form);
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
        async deleteState(id) {
            try {
                // Send DELETE request to the backend
                const response = await axios.delete(`/api/state/${id}`);

                // Update the countries array to reflect the deleted country
                this.states = this.states.filter(state => state.id !== id);

                // Return a success message
                return { status: "success", message: response.data.message };
            } catch (error) {
                const errorMessage =
                    error.response?.data?.message || "Failed to delete the state.";

                // Log the error
                console.error("Error deleting state:", error);
                // Return the error message
                return { status: "error", message: errorMessage };
            }
        },
        async restoreState(id) {
            try {
                const response = await axios.post(`/api/state/${id}/restore`);
                // Refresh state list
                this.states = this.states.filter(state => state.id !== id);
                return { status: "success", message: response.data.message };
            } catch (error) {
                const errorMessage =
                    error.response?.data?.message || "Failed to restore the state.";
                console.error("Error restoring state:", error);
                return { status: "error", message: errorMessage };
            }
        },
        
        async permanentDeleteState(id) {
            try {
                const response = await axios.delete(`/api/state/${id}/force-delete`);
                this.states = this.states.filter(state => state.id !== id);
                return { status: "success", message: response.data.message };
            } catch (error) {
                const errorMessage =
                    error.response?.data?.message || "Failed to permanently delete the state.";
                console.error("Error permanently deleting state:", error);
                return { status: "error", message: errorMessage };
            }
        },
        

        setSort(column, direction) {
            this.sortColumn = column;
            this.sortDirection = direction;
        },
        resetForm() {
            this.form.name = "";
            this.form.country_id = null;
            this.errors = {};
        },
    },
});
