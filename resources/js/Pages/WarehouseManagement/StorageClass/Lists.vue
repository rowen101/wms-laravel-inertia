<script setup>
import {
  mdiAccount,
  mdiAccountGroup, mdiDeleteAlert,
  mdiFileDocumentPlus, mdiMail,
} from "@mdi/js";

import CardBox from "@/Components/CardBox.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import BaseButton from "@/Components/BaseButton.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import { Head,router } from '@inertiajs/vue3'
import CoreTable from "@/Components/CoreTable.vue";
import AsideDrawer from "@/Components/AsideDrawer.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import { ref,watch} from "vue";
import axios from "axios";
import PopupDialog from "@/Components/PopupDialog.vue";
import service from "@/Components/Toast/service";

let toast = service();

let formdata = ref({classcode:'',description:''});
const props = defineProps({ masterlist: Array});
const DataHeader = [
  {label: 'ID', fieldName: 'id',type:'link'},
  {label: 'Class Code', fieldName: 'classcode'},
  {label: 'Description', fieldName: 'description'},
  {label: 'Created By', fieldName: 'creator'},
  {label: 'Create Date', fieldName: 'created_at',type:'datetime'},
]

const tableRows = ref(props.masterlist ?? [])
const openDrawer = ref(false)
const openLink = (linkValue) => {
  formdata.value = linkValue
  openDrawer.value = true
}

const emit = defineEmits(["triggerTopRightButton"]);
const actionClicked = (action) => {
  openDrawer.value = false;
  emit('triggerTopRightButton',action,action === 'Manage' ? formdata.value : null);
}

const deleteRecord =  (id) =>{
   axios.post('/wm/storage-class/delete/'+id)
    .then(response => {
      toast.success(`Item record successfully deleted!`);
      router.visit('/wm/storage-class')
      }).catch(err =>{
        console.log(err)
    });
};

const showPop = ref(false);

const popShow = () =>{
  showPop.value = !showPop.value
  openDrawer.value = false;
} 

const popAction = (action) =>{
  showPop.value = false
  if (action === 'ok') {
    deleteRecord(formdata.value.id)
    openDrawer.value = false;
  }
} 
</script>

<template>

<Head title="StorageClass" />
  <SectionTitleLineWithButton :icon="mdiAccountGroup" title="StorageClass" main>
    <BaseButton
      @click="actionClicked('Add')"
      :icon="mdiFileDocumentPlus "
      label="Add Storage Class"
      color="contrast"
      rounded-full
      small
    />
  </SectionTitleLineWithButton>

  <CardBox class="flex-1 p-6" has-table>
    <CoreTable v-if="tableRows.length > 0"
                :table-rows="tableRows"
                :table-header="DataHeader"
                table-name="suppliers"
               searchable-fields="id,classcode,description"
                :is-paginated="true"
                @openLink="openLink"
    />
    <CardBoxComponentEmpty v-else >Click Add Storage Class button to create your first record</CardBoxComponentEmpty>
  </CardBox>
  <AsideDrawer title="Item Info" :is-open="openDrawer" @closeDrawer="openDrawer = false" class="shadow-lg shadow-blue-500/50">

<FormField label="Class Code">
  <FormControl v-model="formdata.classcode" name="classcode" :disabled="true" />
</FormField>
<FormField label="Description">
  <FormControl v-model="formdata.description" name="description" :disabled="true" />
</FormField>


<BaseButtons class="mt-2">
  <BaseButton color="info" @click="actionClicked('Manage')" label="Manage" />
  <BaseButton color="danger" label="Delete" @click="popShow" outline />
</BaseButtons>
</AsideDrawer>
<!-- Popup Dialog Confirmation-->
<PopupDialog
:open="showPop"
popupTitle="Delete Confirmation"
okButtonText="Yes, Delete"
cancelButtonText="Cancel"
:showCancelButton="true"
:icon=mdiDeleteAlert
theme="danger"
@popaction="popAction"> 
<p class="text-sm text-gray-500">{{ 'Are you sure you want to delete item record ' + (formdata.itemcode +', ' + formdata.description).toLocaleUpperCase() +' ? This action cannot be undone and account will be permanently removed.' }}</p>
</PopupDialog>
</template>
