<script setup>
import Lists from "./Lists.vue";
import Manage from "./Manage.vue";
import { ref, provide } from "vue";

defineProps({
  records: Array,
  currentPage: Number,
  lastPage: Number
});
const action = ref('lists');
let rowdata = ref(null);

const changeWindow = (actionWindow, data) => {
  rowdata.value = data ?? null
  action.value = actionWindow;
}

</script>

<template>
<main class="py-5 lg:col-span-5">
     <div v-show="action === 'lists'">
    <Lists @triggerTopRightButton="changeWindow" :records="records" :currentPage="currentPage" :lastPage="lastPage" />
  </div>
  <div v-show="action === 'Manage' || action === 'Add'">
    <Manage :action="action" :formdata="rowdata" @triggerTopRightButton="changeWindow" />
  </div>
</main>
 

</template>
