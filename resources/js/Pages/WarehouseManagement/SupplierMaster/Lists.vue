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

let formdata = ref({code:'',name:'',category:'',address:''});
const props = defineProps({ masterlist: Array});
const DataHeader = [
  {label: 'ID', fieldName: 'id',type:'link'},
  {label: 'Code', fieldName: 'code'},
  {label: 'Supplier Name', fieldName: 'name'},
  {label: 'Category', fieldName: 'category'},
  {label: 'Status', fieldName: 'status',type:'activeinactive'},
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
   axios.post('/wm/supplier/delete/'+id)
    .then(response => {
      toast.success(`Supplier record successfully deleted!`);
      router.visit('/wm/suppliers')
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

<Head title="suppliers" />
  <SectionTitleLineWithButton :icon="mdiAccountGroup" title="Suppliers" main>
    <BaseButton
      @click="actionClicked('Add')"
      :icon="mdiFileDocumentPlus "
      label="Add Supplier"
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
                searchable-fields="id,code,name,category"
                :is-paginated="true"
                @openLink="openLink"
    />
    <CardBoxComponentEmpty v-else >Click Add Supplier button to create your first record</CardBoxComponentEmpty>
  </CardBox>
  <AsideDrawer title="Supplier Info" :is-open="openDrawer" @closeDrawer="openDrawer = false" class="shadow-lg shadow-blue-500/50">

    <FormField label="Supplier Code">
      <FormControl v-model="formdata.code" name="code" :disabled="true" />
    </FormField>
    <FormField label="Supplier Name">
      <FormControl v-model="formdata.name" name="name" :disabled="true" />
    </FormField>
    <FormField label="Category">
      <FormControl v-model="formdata.category" name="category" :disabled="true"  />
    </FormField>
    <div v-if="formdata.address">
      <FormField :label="formdata.address.streetAddress.label">
        <FormControl v-model="formdata.address.streetAddress.value" name="streetAddress" :disabled="true"/>
      </FormField>
      <FormField :label="formdata.address.streetAddressL2.label">
        <FormControl v-model="formdata.address.streetAddressL2.value" name="streetAddressL2" :disabled="true"/>
      </FormField>
      <FormField :label="formdata.address.city.label">
        <FormControl v-model="formdata.address.city.value" name="city" :disabled="true"/>
      </FormField>
      <FormField :label="formdata.address.province.label">
        <FormControl v-model="formdata.address.province.value" name="province" :disabled="true"/>
      </FormField>
      <FormField :label="formdata.address.postalCode.label">
        <FormControl v-model="formdata.address.postalCode.value" name="postalCode" :disabled="true"/>
      </FormField>
    </div>
    
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
    <p class="text-sm text-gray-500">{{ 'Are you sure you want to delete supplier record ' + (formdata.code +', ' + formdata.name).toLocaleUpperCase() +' ? This action cannot be undone and account will be permanently removed.' }}</p>
  </PopupDialog>
</template>
