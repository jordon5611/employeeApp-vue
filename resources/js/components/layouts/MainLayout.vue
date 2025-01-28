<template>
    <div :class="{ 'rtl': currentLanguage === 'ur', 'ltr': currentLanguage === 'en' }" class="px-10">
        <nav class="flex justify-between items-center py-4 border-b border-white">
            <div class="space-x-7 font-bold px-5">
                <router-link to="/country" class="px-3"
                    :class="{ 'text-gray-300': isActive('country'), 'hover:text-purple-500': !isActive('country') }">
                    {{ layoutTranslations.country }}
                </router-link>
                <router-link to="/state"
                    :class="{ 'text-gray-300': isActive('state'), 'hover:text-purple-500': !isActive('state') }">
                    {{ layoutTranslations.state }}
                </router-link>
                <router-link to="/city"
                    :class="{ 'text-gray-300': isActive('city'), 'hover:text-purple-500': !isActive('city') }">
                    {{ layoutTranslations.city }}
                </router-link>
                <router-link to="/employee"
                    :class="{ 'text-gray-300': isActive('employee'), 'hover:text-purple-500': !isActive('employee') }">
                    {{ layoutTranslations.employee }}
                </router-link>
            </div>


            <div class="language-switcher px-5">
                <select v-model="currentLanguage" class="form-select form-select-sm bg-black text-white"
                    @change="changeLanguage">
                    <option value="en">English</option>
                    <option value="ur">اردو</option>
                </select>
            </div>
        </nav>

        <main class="mt-10 max-w-[1200px] mx-auto pb-20">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
import { useRoute } from 'vue-router';
import { computed, ref, onMounted } from 'vue';
import { useTranslationStore } from "@/stores/translationStore"; // Import Pinia translation store
import axios from 'axios';


export default {
    name: 'MainLayout',

    setup() {
        const route = useRoute();
        const translationStore = useTranslationStore(); // Access Pinia translation store
        const currentLanguage = ref(translationStore.locale); // Bind to the store's locale

        // Computed translations for layout
        const layoutTranslations = computed(() => translationStore.translations.layout || {});

        // Determine if a route is active
        const isActive = (path) => {
            return computed(() => route.path.includes(path)).value;
        };

        // Change language and fetch updated translations
        const changeLanguage = async () => {
            try {
                // Update the locale using the store's setLocale method
                await translationStore.setLocale(currentLanguage.value);

                // Store the updated locale in localStorage
                localStorage.setItem('locale', currentLanguage.value);

                window.location.reload();

                document.documentElement.lang = currentLanguage.value; // Update HTML lang attribute
            } catch (error) {
                console.error("Error switching language:", error);
            }
        };

        const fetchCurrentLanguage = async () => {
            try {
                const response = await axios.get("/api/locale");
                const locale = response.data.locale; // Default to English
                console.log(response.data.locale);

                currentLanguage.value = locale;

                // Store the locale in localStorage for persistence
                localStorage.setItem('locale', locale);

                await translationStore.setLocale(locale); // Load translations
                document.documentElement.lang = locale;
            } catch (error) {
                console.error("Error fetching current locale:", error);
                currentLanguage.value = "en"; // Fallback to English
                await translationStore.setLocale("en");
            }
        };

        // Fetch current locale on component mount
        onMounted(() => {
            const storedLocale = localStorage.getItem('locale');
            console.log('MainLayout mounted', translationStore.locale);
            if (storedLocale) {
                // If a stored locale exists, set it immediately
                translationStore.setLocale(storedLocale).then(() => {
                    currentLanguage.value = storedLocale;
                    document.documentElement.lang = storedLocale;
                });
            } else {
                fetchCurrentLanguage();
            }



        });

        return {
            isActive,
            currentLanguage,
            changeLanguage,
            layoutTranslations
        };
    },
};
</script>
