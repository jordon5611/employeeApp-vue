<template>
    <div class="mb-4">
      <label :for="name" class="block text-white font-bold mb-2">
        {{ label }}:
      </label>
  
      <select
        :id="name"
        :name="name"
        class="w-half px-4 py-2 text-black rounded-lg"
        :required="required"
        v-model="selectedValue"
      >
        <option v-if="placeholder" value="">{{ placeholder }}</option>
        <option
          v-for="option in normalizedOptions"
          :key="option.value"
          :value="option.value"
          :selected="option.value === selectedValue"
        >
          {{ option.text }}
        </option>
      </select>
    </div>
  </template>
  
  <script>
  import { defineComponent, computed, ref } from "vue";
  
  export default defineComponent({
    name: "FormSelect",
    props: {
      name: {
        type: String,
        required: true,
      },
      label: {
        type: String,
        required: true,
      },
      options: {
        type: [Array, Object],
        default: () => [],
      },
      selected: {
        type: [String, Number],
        default: null,
      },
      placeholder: {
        type: String,
        default: null,
      },
      required: {
        type: Boolean,
        default: false,
      },
    },
    setup(props) {
      const selectedValue = ref(props.selected);
  
      const normalizedOptions = computed(() => {
        if (Array.isArray(props.options)) {
          return props.options.map((option) => ({
            value: option.id,
            text: option.name,
          }));
        }
  
        return Object.entries(props.options).map(([value, text]) => ({
          value,
          text,
        }));
      });
  
      return {
        selectedValue,
        normalizedOptions,
      };
    },
  });
  </script>
  