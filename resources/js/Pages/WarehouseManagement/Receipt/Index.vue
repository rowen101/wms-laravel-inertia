<script setup>
import Lists from "./Lists.vue";
import Manage from "./Manage.vue";
import {ref} from "vue";

const props = defineProps({listdata:Array});
const action = ref('listview');
let rowdata = ref(null);

const changeWindow = (actionWindow,data) => {
  rowdata.value = data ?? null
  action.value = actionWindow;
}
</script>

<template>

  <div v-show="action === 'listview'">
    <Lists :listdata="props.listdata" @triggerTopRightButton="changeWindow"/>
  </div>
  <div v-show="action === 'formview' || action ==='Add'">
    <Manage :action="action" :formdata="rowdata" @triggerTopRightButton="changeWindow"/>
  </div>
  
</template>
