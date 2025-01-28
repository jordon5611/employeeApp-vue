import './bootstrap';

import Swal from 'sweetalert2';
window.Swal = Swal;

import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './App.vue';
import FormInput from "@/components/FormInput.vue";
import Button from "@/components/Button.vue";

const pinia = createPinia();
const app = createApp(App);

app.component('FormInput', FormInput);
app.component('Button', Button);
app.use(router);
app.use(pinia);
app.mount('#app');