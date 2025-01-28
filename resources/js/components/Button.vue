<template>
  <button type="submit" :class="[
    'bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300',
  ]">
    {{ isEditMode ? updateText : createText }}
  </button>
</template>

<script>
import { defineComponent, computed } from "vue";

export default defineComponent({
  name: "Button",
  props: {
    isEditMode: {
      type: Boolean,
      required: true, // Ensure that Create.vue always passes this prop
    },
    createText: {
      type: String,
      default: "Create",
    },
    updateText: {
      type: String,
      default: "Update",
    },
  },
  // setup(props) {
  //   const isCreateRoute = computed(() =>
  //     window.location.pathname.includes("create")
  //   );

  //   return {
  //     isCreateRoute,
  //   };
  // },
});
</script>