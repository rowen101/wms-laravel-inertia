<template>
    <div>
        <div v-if="isSearchVisible" class="fixed inset-0 bg-black bg-opacity-50 backdrop" @click="toggleSearch"></div>
        <div class="fixed top-0 left-0 right-0 mt-48 flex items-center justify-center input-search"
            v-if="isSearchVisible">
            <div class="relative w-full max-w-md">
                <div class="relative">
                    <input type="text" v-model="searchQuery" placeholder="Search..." @input="onSearch"
                        @keydown.down="onArrowDown" @keydown.up="onArrowUp" @keydown.enter="onEnter" ref="searchInput"
                        class="w-full px-10 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <BaseIcon :path="mdiMagnify" size="24"/>
                    </div>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer" @click="toggleSearch">
                        <span class="text-gray-400">ESC</span>
                    </div>
                </div>
                <ul v-if="searchResults.length > 0"
                    class="absolute w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                    <li v-for="(result, index) in searchResults" :key="result.id"
                        :class="['px-4 py-2 cursor-pointer', { 'bg-gray-100': index === selectedIndex }]"
                        @click="onSelectResult(result)">
                        {{ result.name }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick,inject } from 'vue';
import _ from 'lodash';
import BaseIcon from "@/Components/BaseIcon.vue";
import {  mdiMagnify } from "@mdi/js";
import { usePage,router } from '@inertiajs/vue3';

const isSearchVisible = ref(false);
const searchQuery = ref('');
const searchResults = ref([]);
const selectedIndex = ref(-1);
const searchInput = ref(null);
const dataList = inject('dataList');

const handleKeydown = (event) => {
    if (event.ctrlKey && event.key === 'k') {
        event.preventDefault();
        toggleSearch();
    }
    if (event.key === 'Escape') {
        toggleSearch();
    }
};

const toggleSearch = () => {
    isSearchVisible.value = !isSearchVisible.value;
    if (isSearchVisible.value) {
        nextTick(() => {
            searchInput.value.focus();
        });
    } else {
        searchQuery.value = '';
        searchResults.value = [];
        selectedIndex.value = -1;
    }
};

const onSearch = () => {
    if (searchQuery.value) {
        searchResults.value = dataList.value.filter(item =>
            item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
        selectedIndex.value = -1;
    } else {
        searchResults.value = [];
    }
};

const onArrowDown = () => {
    if (selectedIndex.value < searchResults.value.length - 1) {
        selectedIndex.value++;
    }
};

const onArrowUp = () => {
    if (selectedIndex.value > 0) {
        selectedIndex.value--;
    }
};

const onEnter = () => {
    if (selectedIndex.value >= 0) {
        onSelectResult(searchResults.value[selectedIndex.value]);
    }
};

const onSelectResult = (result) => {
    searchQuery.value = result.name;
    searchResults.value = [];
    router.visit(result.route);
    isSearchVisible.value = false;
    searchQuery.value = '';
};

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});



onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeydown);
});


</script>

<style scoped>
/* Add any additional styles here if needed */
.backdrop {
    z-index: 70;
}

.input-search {
    z-index: 80;
    top: -100px;
}
</style>