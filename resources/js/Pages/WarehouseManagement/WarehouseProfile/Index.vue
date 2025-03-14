<script setup>
import Lists from "./Lists.vue";
import Manage from "./Manage.vue";
import {ref,provide} from "vue";

const props = defineProps({
  records: Array,
  currentPage: Number,
  lastPage: Number,
  storageClass: Array
});

provide('storageClass', props.storageClass);

const action = ref('lists');
let rowdata = ref(null);

const changeWindow = (actionWindow,data) => {
  rowdata.value = data ?? null
  action.value = actionWindow;
}
</script>

<template>

  <div v-show="action === 'lists'">
    <Lists :records="records" @triggerTopRightButton="changeWindow"/>
  </div>
  <div v-show="action === 'Manage' || action ==='Add'">

    <Manage :action="action" :formdata="rowdata" @triggerTopRightButton="changeWindow"/>
  </div>
  
</template>
