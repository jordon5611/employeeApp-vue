// stores/translationStore.js

import { defineStore } from 'pinia';
import axios from 'axios';



export const useTranslationStore = defineStore('translation', {
  state: () => ({
    translations: {}, // Stores all translations
    locale: 'en', // Default locale
  }),
  actions: {
    async fetchTranslations(locale) {
      try {
        const response = await axios.get(`/api/translations/${locale}`);
        this.translations = response.data.translations;
        this.locale = locale;
      } catch (error) {
        console.error('Error fetching translations:', error);
      }
    },
    async setLocale(locale) {
      try {
        // Update the locale on the backend (e.g., session or cookie-based)
        await axios.get(`/api/locale/${locale}`);
        this.locale = locale;
        // Fetch updated translations for the new locale
        await this.fetchTranslations(locale);
      } catch (error) {
        console.error("Error setting locale:", error);
      }
    },
  },
  getters: {
    getTranslation: (state) => (namespace, key) => {
      return state.translations[namespace]?.[key] || key; // Fallback to the key itself
    },
  },
});
