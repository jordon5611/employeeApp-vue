
<template>
    <div class="mb-4">
        <label :for="id" class="block text-white font-bold mb-2">
            {{ label }}:
        </label>
        <input :type="type" :id="id" :name="name" class="w-half px-4 py-2 text-black rounded-lg"
            :placeholder="placeholder" :value="isNumericOnly ? formattedValue : modelValue" @input="handleInput"
            @keydown="restrictInput" :required="required" />
        <!-- Error message -->
        <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    name: "FormInput",
    props: {
        id: {
            type: String,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            default: "text",
        },
        placeholder: {
            type: String,
            default: "",
        },
        modelValue: {
            type: [String, Number],
            default: "",
        },
        required: {
            type: Boolean,
            default: false,
        },
        error: {
            type: String,
            default: "",
        },
        isNumericOnly: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        formattedValue() {
            if (!this.isNumericOnly) return this.modelValue;
            const value = this.modelValue.toString().replace(/\D/g, ""); // Remove non-numeric characters
            if (value.length <= 3) return value;
            if (value.length <= 6) return `${value.slice(0, 3)}-${value.slice(3)}`;
            return `${value.slice(0, 3)}-${value.slice(3, 6)}-${value.slice(6, 10)}`;
        },
    },
    methods: {
        handleInput(event) {
            let rawValue = event.target.value;
            if (this.isNumericOnly) {
                rawValue = rawValue.replace(/\D/g, ""); // Remove non-numeric characters
            }
            this.$emit("update:modelValue", rawValue); // Emit the value (formatted or raw)
        },
        restrictInput(event) {
            if (this.isNumericOnly) {
                const allowedKeys = [
                    "Backspace",
                    "ArrowLeft",
                    "ArrowRight",
                    "Tab",
                    "Delete",
                ];
                const isNumberKey = /^[0-9]$/.test(event.key);

                // Allow only numeric keys, navigation keys, and control keys
                if (!isNumberKey && !allowedKeys.includes(event.key)) {
                    event.preventDefault();
                }
            }
        },
    },
});
</script>