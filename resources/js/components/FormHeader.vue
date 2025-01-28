<template>
    <div>
      <h1 class="text-3xl font-bold mb-6">
        {{ isCreateRoute ? titleCreate : titleEdit }}
      </h1>
      <form
        :action="isCreateRoute ? routeCreate : `${routeUpdate}/${item?.id || ''}`"
        method="POST"
      >
        <input type="hidden" name="_method" v-if="!isCreateRoute" value="PUT" />
        <slot></slot>
      </form>
    </div>
  </template>
  
  <script>
  import { defineComponent, computed } from "vue";
  
  export default defineComponent({
    name: "FormHeader",
    props: {
      routeCreate: {
        type: String,
        required: true,
      },
      routeUpdate: {
        type: String,
        required: true,
      },
      titleCreate: {
        type: String,
        required: true,
      },
      titleEdit: {
        type: String,
        required: true,
      },
      item: {
        type: Object,
        default: null,
      },
    },
    setup(props) {
      const isCreateRoute = computed(() =>
        window.location.pathname.includes("create")
      );
  
      return {
        isCreateRoute,
      };
    },
  });
  </script>
  