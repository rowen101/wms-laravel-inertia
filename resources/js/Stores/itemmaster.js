// stores/itemMaster.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useItemMasterStore = defineStore('itemmaster', {
  state: () => ({
    masterlists: null,
  }),
  actions: {
    async initialize() {
      try {
        const response = await axios.get(`${window.location.origin}/wm/warehouse-items`);
        if (response.data) {
          this.masterlists = response.data;
        }
      } catch (error) {
        console.error('Error fetching warehouse items:', error.message);
      }
    },
    setItems(masterlistdata) {
      this.masterlists = masterlistdata;
    },
  },
});
