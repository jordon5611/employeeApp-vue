import { defineStore } from "pinia";
import axios from "axios";

export const useCountryStore = defineStore("country", {
    state: () => ({
        countries: [],
        sortColumn: "id",
        sortDirection: "asc",
        search: "", // Search query
        pagination: {}, // Store pagination metadata
        form: {
            name: "",
        },
        errors: {},
    }),
    actions: {
        async fetchCountries({ search = "", sort = "id", direction = "asc", archived = false, url = "/api/country" } = {},) {
            try {

                // Extract existing query parameters from the provided URL
                const urlObj = new URL(url, window.location.origin); // Ensure URL is absolute
                const params = new URLSearchParams(urlObj.search);

                // Update query parameters (override or add new ones)
                params.set("search", search);
                params.set("sort", sort);
                params.set("direction", direction);

                // Check if archived view is requested and add query param

                params.set("archived", archived);


                // Update the browser's URL without reloading the page
                const webUrl = new URL(window.location.href);
                webUrl.search = params.toString(); // Update query parameters in the browser's URL
                window.history.replaceState({}, '', webUrl);

                const response = await axios.get(urlObj.pathname, {
                    params, // Pass the updated query parameters
                });

                // Update store with the fetched data
                this.countries = response.data.data;
                this.pagination = response.data;

                // Save the state of search, sort, and direction
                this.search = search;
                this.sortColumn = sort;
                this.sortDirection = direction;
            } catch (error) {
                console.error("Error fetching countries:", error);
            }
        },
        async fetchCountry(id) {
            try {
                const response = await axios.get(`/api/country/${id}`);
                this.form.name = response.data.name;
            } catch (error) {
                // Handle errors from API
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                }
                console.error("Error fetching country:", error);
            }
        },
        async createCountry() {
            try {
                const response = await axios.post('/api/country', {
                    name: this.form.name, // Ensure you're passing the correct data
                });
                if (response.data.status === 'success') {
                    this.resetForm();
                }
                return response.data;
            } catch (error) {
                // Handle errors from API
                if (error.response && error.response.data.errors) {
                    console.log("Errors from API:", error.response.data.errors);
                    this.errors = error.response.data.errors;
                }
                throw error; // Throw error for handling in the component
            }
        },
        async updateCountry(id) {
            try {
                const response = await axios.put(`/api/country/${id}`, this.form);
                if (response.data.status === 'success') {
                    this.resetForm();
                }
                return response.data;
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error("Error updating country:", error);
                }
                throw error;
            }
        },

        async restoreCountry(id) {
            try {
                const response = await axios.post(`/api/country/${id}/restore`);
                this.countries = this.countries.filter(country => country.id !== id);

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

        async forceDeleteCountry(id) {
            try {
                const response = await axios.delete(`/api/country/${id}/force-delete`);
                this.countries = this.countries.filter(country => country.id !== id);

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
        resetForm() {
            this.form.name = "";
            this.errors = {};
        },

        setCountries(countries) {
            this.countries = countries;
        },

        setSort(column, direction) {
            this.sortColumn = column;
            this.sortDirection = direction;
        },

        // New method to delete country and update the state
        async deleteCountry(id) {
            try {
                // Send DELETE request to the backend
                const response = await axios.delete(`/api/country/${id}`);

                // Update the countries array to reflect the deleted country
                this.countries = this.countries.filter(country => country.id !== id);

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
    },
});
