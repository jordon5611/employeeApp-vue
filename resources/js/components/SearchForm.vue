<template>
  <div class="max-w-2xl mx-auto">
    <form @submit.prevent="handleSearch" class="mb-6 flex gap-2">
      <input type="text" name="search" v-model="search" class="flex-1 px-4 py-2 text-black rounded-lg"
        :placeholder="placeholder" />
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{getTranslation("components", "search_button")}}
      </button>
    </form>
  </div>
</template>


<script>
import { ref, defineComponent } from "vue";
import { useTranslationStore } from "@/stores/translationStore";


export default defineComponent({
  name: "SearchForm",
  props: {
    route: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      default: "Search...",
    },
  },
  setup(_, { emit }) {
    const translationStore = useTranslationStore();

    // Get translations dynamically
    const getTranslation = translationStore.getTranslation;
    const search = ref("");

    const handleSearch = () => {
      emit("search", search.value); // Emit the search event with the query
    };

    return {
      search,
      handleSearch,
      getTranslation
    };
  },
});
</script>