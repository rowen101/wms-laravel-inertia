<script setup>
import {
  mdiTruckCargoContainer,
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
const props = defineProps({ listdata: Array});
const userHeader = [
  {label: 'ID', fieldName: 'id',type:'link'},
  {label: 'Receipt No', fieldName: 'receiptno'},
  {label: 'Receipt Date', fieldName: 'receiptdate',type:"date"},
  {label: 'Reference No', fieldName: 'referenceno'},
  {label: 'Supplier Code', fieldName: 'supplier.code'},
  {label: 'Supplier Name', fieldName: 'supplier.name'},
  {label: 'Created By', fieldName: 'creator'},
  {label: 'Create Date', fieldName: 'created_at',type:'datetime'},
]

const tableRows = ref(props.listdata ?? [])
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
   axios.post('/im/receipt/delete/'+id)
    .then(response => {
      toast.success(`Receipt record successfully deleted!`);
      router.visit('/im/receive-shipment')
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

<Head title="receipts" />
  <SectionTitleLineWithButton :icon="mdiTruckCargoContainer" title="Receive Shipments" main>
    <BaseButton
      @click="actionClicked('Add')"
      :icon="mdiFileDocumentPlus "
      label="New"
      color="contrast"
      rounded-full
      small
    />
  </SectionTitleLineWithButton>

  <CardBox class="flex-1 p-6" has-table>
    <CoreTable v-if="tableRows.length > 0"
                :table-rows="tableRows"
                :table-header="userHeader"
                table-name="receipts"
                searchable-fields="receiptno,referenceno,supplier.code,supplier.name"
                :is-paginated="true"
                @openLink="openLink"
    />
    <CardBoxComponentEmpty v-else >Click New button to receive shipments.
    </CardBoxComponentEmpty>
  </CardBox>
  <AsideDrawer title="Receipt Info" :is-open="openDrawer" @closeDrawer="openDrawer = false" class="shadow-lg shadow-blue-500/50">

    <FormField label="Receipt No">
      <FormControl v-model="formdata.receiptno" name="receiptno" :disabled="true" />
    </FormField>
    <FormField label="Receipt Date">
      <FormControl v-model="formdata.receiptdate" name="receiptdate" :disabled="true" />
    </FormField>
    <FormField label="Reference No">
      <FormControl v-model="formdata.referenceno" name="referenceno" :disabled="true"  />
    </FormField>
    <FormField v-if="formdata.supplier" label="Supplier Code">
      <FormControl v-model="formdata.supplier.code" name="suppliercode" :disabled="true"  />
    </FormField>
    <FormField v-if="formdata.supplier" label="Supplier Name">
      <FormControl v-model="formdata.supplier.name" name="suplliername" :disabled="true"  />
    </FormField>
    <FormField label="Status">
      <FormControl v-model="formdata.status" name="status" :disabled="true"  />
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
    <p class="text-sm text-gray-500">{{ 'Are you sure you want to delete record ' + (formdata.receiptno).toLocaleUpperCase() +' ? This action cannot be undone and account will be permanently removed.' }}</p>
  </PopupDialog>
</template>
