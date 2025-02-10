

<template>
    <tr>
        <template v-for="(cell, index) in data" :key="index">
            <td class="border border-white px-4 py-2">{{ cell }}</td>
        </template>
        <td class="border border-white px-4 py-2">
            <div class="flex gap-1">
                <template v-if="!showArchived">
                    <template v-if="detailsRoute">
                        <a :href="`${detailsRoute}/${data.id}`"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-300">
                            Show
                        </a>
                    </template>
                    <a :href="`${editRoute}/${data.id}`"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                        {{ componentTranslations.edit }}
                    </a>
                    <button @click="confirmDelete"
                        class="bg-red-500 text-white px-3 py-2 rounded hover:bg-red-700 transition duration-300">
                        {{ componentTranslations.delete }}
                    </button>
                </template>

                <!-- Show Restore & Permanent Delete for Soft Deleted Records -->
                <template v-else>
                    <button @click="confirmRestore"
                        class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-700 transition duration-300">
                        {{ componentTranslations.restore }}
                    </button>
                    <button @click="confirmPermanentDelete"
                        class="bg-red-700 text-white px-3 py-2 rounded hover:bg-red-900 transition duration-300">
                        {{ componentTranslations.permanentDelete }}
                    </button>
                </template>
            </div>
        </td>
    </tr>
</template>

<script>
import { defineComponent, computed } from "vue";
import Swal from "sweetalert2";
import { useTranslationStore } from "@/stores/translationStore";
import { useCountryStore } from "@/stores/countryStore";

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
            required: true,
        },
        restoreFunction: {
            type: Function,
            required: true,
        },
        permanentDeleteFunction: {
            type: Function,
            required: true,
        },
        showArchived: {
            type: Boolean,
            default: false,
        },
    },
    setup(props) {
        const countryStore = useCountryStore();
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
                        const response = await props.deleteFunction(props.data.id);
                        if (response.status === 'error') {
                            Swal.fire(translationStore.translations.popups?.delete_cancel?.title, response.message || "Failed to delete", "error");
                            return;
                        }
                        await Swal.fire(translationStore.translations.popups?.delete_success?.title, `${response.message}`, "success");
                    } catch (error) {
                        const errorMessage = error.response?.data?.message || error.message || "An unknown error occurred while deleting the record.";
                        Swal.fire(translationStore.translations.popups?.delete_cancel?.title, errorMessage, "error");
                    }
                }
            });
        };

        const confirmRestore = () => {
            Swal.fire({
                title: translationStore.translations.popups?.restore_confirmation?.title,
                text: translationStore.translations.popups?.restore_confirmation?.text,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: translationStore.translations.popups?.restore_confirmation?.confirm_button,
                cancelButtonText: translationStore.translations.popups?.restore_confirmation?.cancel_button,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await props.restoreFunction(props.data.id);
                        if (response.status === 'error') {
                            Swal.fire(translationStore.translations.popups?.restore_cancel?.title, response.message || "Failed to restore", "error");
                            return;
                        }
                        await Swal.fire(translationStore.translations.popups?.restore_success?.title, `${response.message}`, "success");
                    } catch (error) {
                        const errorMessage = error.response?.data?.message || error.message || "An unknown error occurred while restoring the record.";
                        Swal.fire(translationStore.translations.popups?.restore_cancel?.title, errorMessage, "error");
                    }
                }
            });
        };

        const confirmPermanentDelete = () => {
            Swal.fire({
                title: translationStore.translations.popups?.permanent_delete_confirmation?.title,
                text: translationStore.translations.popups?.permanent_delete_confirmation?.text,
                icon: "error",
                showCancelButton: true,
                confirmButtonText: translationStore.translations.popups?.permanent_delete_confirmation?.confirm_button,
                cancelButtonText: translationStore.translations.popups?.permanent_delete_confirmation?.cancel_button,
                reverseButtons: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await props.permanentDeleteFunction(props.data.id);
                        if (response.status === 'error') {
                            Swal.fire(translationStore.translations.popups?.permanent_delete_cancel?.title, response.message || "Failed to delete permanently", "error");
                            return;
                        }
                        await Swal.fire(translationStore.translations.popups?.permanent_delete_success?.title, `${response.message}`, "success");
                    } catch (error) {
                        const errorMessage = error.response?.data?.message || error.message || "An unknown error occurred while permanently deleting the record.";
                        Swal.fire(translationStore.translations.popups?.permanent_delete_cancel?.title, errorMessage, "error");
                    }
                }
            });
        };


        return {
            confirmDelete,
            confirmRestore,
            confirmPermanentDelete,
            componentTranslations,
        };
    },
});
</script>
