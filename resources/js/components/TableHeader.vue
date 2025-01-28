<template>
    <thead :class="languageDirection">
        <tr class="bg-gray-800">
            <template v-for="(label, column) in columns" :key="column">
                <th class="border border-white px-4 py-2">
                    <button @click="handleSort(column)" class="hover:underline">
                        {{ label }}
                        <template v-if="sortColumn === column">
                            <span v-if="sortDirection === 'asc'">▲</span>
                            <span v-else>▼</span>
                        </template>
                    </button>
                </th>
            </template>
            <th class="border border-white px-4 py-2"> {{getTranslation("components", "actions")}}</th>
        </tr>
    </thead>
</template>

<script>
import { defineComponent, computed } from "vue";
import { useTranslationStore } from "@/stores/translationStore";

export default defineComponent({
    name: "TableHeader",
    props: {
        columns: {
            type: Object,
            required: true,
        },
        sortColumn: {
            type: String,
            default: null,
        },
        sortDirection: {
            type: String,
            default: null,
        },
        route: {
            type: String,
            required: true,
        },
    },

    setup() {
        const translationStore = useTranslationStore();
        // Get translations dynamically
        const getTranslation = translationStore.getTranslation;

        // Dynamically set direction based on the current language
        const languageDirection = computed(() => {
            return translationStore.locale === "ur" ? "rtl" : "ltr";
        });

        return { languageDirection, getTranslation };
    },

    emits: ["sort"],
    methods: {
        handleSort(column) {
            const direction =
                this.sortColumn === column && this.sortDirection === "asc"
                    ? "desc"
                    : "asc";
            this.$emit("sort", { column, direction });

        },
    },

});
</script>