<script setup>
import Lists from "./Lists.vue";
import Manage from "./Manage.vue";
import {ref} from "vue";

const props = defineProps({masterdata:Object});
const action = ref('lists');
let rowdata = ref(null);

const changeWindow = (actionWindow,data) => {
  rowdata.value = data ?? null
  action.value = actionWindow;
}
</script>

<template>

  <div v-show="action === 'lists'">
    <Lists :masterlist="props.masterdata.lists" @triggerTopRightButton="changeWindow"/>
  </div>
  <div v-show="action === 'Manage' || action ==='Add'">
    <Manage :action="action" :formdata="rowdata" :storageclass="masterdata.storageclass" @triggerTopRightButton="changeWindow"/>
  </div>
  
</template>
