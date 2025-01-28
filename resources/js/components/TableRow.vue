<template>
    <tr>
        <template v-for="(cell, index) in data" :key="index">
            <td class="border border-white px-4 py-2">{{ cell }}</td>
        </template>
        <td class="border border-white px-4 py-2">
            <div class="flex gap-1">
                <template v-if="detailsRoute">
                    <a :href="`${detailsRoute}/${data.id}`"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300">
                        Show
                    </a>
                </template>
                <a :href="`${editRoute}/${data.id}`"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                    {{componentTranslations.edit}}
                </a>
                <button @click="confirmDelete"
                    class="delete-btn bg-red-500 text-white px-3 py-2 rounded hover:bg-red-700 transition duration-300">
                    {{componentTranslations.delete}}
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
import { defineComponent, computed } from "vue";
import Swal from "sweetalert2";
import { useTranslationStore } from "@/stores/translationStore"; // Import Pinia translation store
import { useCountryStore } from "@/stores/countryStore"; // Import the store


export default defineComponent({
    name: "TableRow",
    props: {
        data: {
            type: Object,
            required: true,
        },
        editRoute: {
            type: String,
            required: true,
        },
        deleteRoute: {
            type: String,
            required: true,
        },
        detailsRoute: {
            type: String,
            default: null,
        },
        deleteFunction: {
            type: Function,
            required: true, // This will dynamically pass the delete method
        },
    },
    setup(props) {
        const countryStore = useCountryStore(); // Use the store
        const translationStore = useTranslationStore();
        const componentTranslations = computed(() => translationStore.translations.components || {});

        const confirmDelete = () => {
            Swal.fire({
                title: translationStore.translations.popups?.delete_confirmation?.title,
                text: translationStore.translations.popups?.delete_confirmation?.text,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: translationStore.translations.popups?.delete_confirmation?.confirm_button,
                cancelButtonText: translationStore.translations.popups?.delete_confirmation?.cancel_button,
                reverseButtons: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        // Use the dynamic deleteFunction passed via props
                        const response = await props.deleteFunction(props.data.id);

                        console.log("Response from deleteFunction:", response);
                        // Check if the response contains an error or non-200 status
                        if (response.status === 'error') {
                            console.log("Error deleting record:", response.message || "Failed to delete");
                            Swal.fire(translationStore.translations.popups?.delete_cancel?.title, response.message || "Failed to delete", "error");
                            return; // Exit early to avoid showing success popup
                        } else {
                            // Display success message
                            await Swal.fire(translationStore.translations.popups?.delete_success?.title, `${response.message}`, "success");
                        }


                    } catch (error) {
                        // Inspect the full error object in the console
                        console.error("Error object details:", error);

                        // Extract the backend error message or fallback to generic error message
                        const errorMessage =
                            error.response?.data?.message || // Backend error message (if available)
                            error.message || // Standard JS error message
                            "An unknown error occurred while deleting the record."; // Default error message

                        // Display error in Swal
                        Swal.fire(translationStore.translations.popups?.delete_cancel?.title, errorMessage, "error");
                    }
                } else {
                    Swal.fire(translationStore.translations.popups?.delete_cancel?.title, translationStore.translations.popups?.delete_cancel?.text, "error");
                }
            });
        };

        return {
            confirmDelete,
            componentTranslations
        };
    },
});
</script>