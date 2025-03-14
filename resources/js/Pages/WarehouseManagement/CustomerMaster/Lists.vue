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
import { Head, router } from '@inertiajs/vue3'
import CoreTable from "@/Components/CoreTable.vue";
import AsideDrawer from "@/Components/AsideDrawer.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import { ref, watch, inject } from "vue";
import axios from "axios";
import PopupDialog from "@/Components/PopupDialog.vue";
import service from "@/Components/Toast/service";
import { WhenVisible } from '@inertiajs/vue3'
let toast = service();

let formdata = ref({ code: '', name: '', category: '', address: '' });


defineProps({
  records: Array,
  currentPage: Number,
  lastPage: Number
});
const userHeader = [
  { label: 'ID', fieldName: 'id', type: 'link' },
  { label: 'Code', fieldName: 'code' },
  { label: 'Customer Name', fieldName: 'name' },
  { label: 'Category', fieldName: 'category' },
  {label: 'Status', fieldName: 'status',type:'activeinactive'},
  { label: 'Created By', fieldName: 'creator' },
  { label: 'Create Date', fieldName: 'created_at', type: 'datetime' },
]

const openDrawer = ref(false)
const openLink = (linkValue) => {
  formdata.value = linkValue
  openDrawer.value = true
}

const emit = defineEmits(["triggerTopRightButton"]);
const actionClicked = (action) => {
  openDrawer.value = false;
  emit('triggerTopRightButton', action, action === 'Manage' ? formdata.value : null);
}

const deleteRecord = (id) => {
  axios.post('/wm/customer/delete/' + id)
    .then(response => {
      toast.success(`Customer record successfully deleted!`);
      router.visit('/wm/customers')
    }).catch(err => {
      console.log(err)
    });
};

const showPop = ref(false);

const popShow = () => {
  showPop.value = !showPop.value
  openDrawer.value = false;
}

const popAction = (action) => {
  showPop.value = false
  if (action === 'ok') {
    deleteRecord(formdata.value.id)
    openDrawer.value = false;
  }
} 
</script>

<template>

  <Head title="customers" />
  
  <SectionTitleLineWithButton :icon="mdiAccountGroup" title="Customers" main>
    <BaseButton @click="actionClicked('Add')" :icon="mdiFileDocumentPlus" label="Add Customer" color="contrast"
      rounded-full small />
  </SectionTitleLineWithButton>

  <CardBox class="flex-1 p-6" has-table>
    <CoreTable v-if="records.length > 0" :table-rows="records" :table-header="userHeader" table-name="customers"
      searchable-fields="id,code,name,category" :is-paginated="true" @openLink="openLink" :show-when-visible="false" />
    <CardBoxComponentEmpty v-else>Click Add Customer button to create your first record</CardBoxComponentEmpty>
  </CardBox>
  <WhenVisible v-if="records.length <= 100"
          :buffer="500"
          always
          :params="{
            data: {
              page: currentPage + 1,
            },
            only: ['records', 'currentPage'],
            preserveUrl: true,
          }"
        >
          <div v-show="currentPage < lastPage">Loading...</div>
        </WhenVisible>

  <AsideDrawer title="Customer Info" :is-open="openDrawer" @closeDrawer="openDrawer = false"
    class="shadow-lg shadow-blue-500/50">

    <FormField label="Customer Code">
      <FormControl v-model="formdata.code" name="code" :disabled="true" />
    </FormField>
    <FormField label="Customer Name">
      <FormControl v-model="formdata.name" name="name" :disabled="true" />
    </FormField>
    <FormField label="Category">
      <FormControl v-model="formdata.category" name="category" :disabled="true" />
    </FormField>
    <div v-if="formdata.address">
      <FormField :label="formdata.address.streetAddress.label">
        <FormControl v-model="formdata.address.streetAddress.value" name="streetAddress" :disabled="true" />
      </FormField>
      <FormField :label="formdata.address.streetAddressL2.label">
        <FormControl v-model="formdata.address.streetAddressL2.value" name="streetAddressL2" :disabled="true" />
      </FormField>
      <FormField :label="formdata.address.city.label">
        <FormControl v-model="formdata.address.city" name="city" :disabled="true" />
      </FormField>
      <FormField :label="formdata.address.province.label">
        <FormControl v-model="formdata.address.province" name="province" :disabled="true" />
      </FormField>
      <FormField :label="formdata.address.postalCode.label">
        <FormControl v-model="formdata.address.postalCode.value" name="postalCode" :disabled="true" />
      </FormField>
    </div>

    <BaseButtons class="mt-2">
      <BaseButton color="info" @click="actionClicked('Manage')" label="Manage" />
      <BaseButton color="danger" label="Delete" @click="popShow" outline />
    </BaseButtons>
  </AsideDrawer>
  <!-- Popup Dialog Confirmation-->
  <PopupDialog :open="showPop" popupTitle="Delete Confirmation" okButtonText="Yes, Delete" cancelButtonText="Cancel"
    :showCancelButton="true" :icon=mdiDeleteAlert theme="danger" @popaction="popAction">
    <p class="text-sm text-gray-500">{{ 'Are you sure you want to delete customer record ' + (formdata.code + ', ' +
      formdata.name).toLocaleUpperCase() + ' ? This action cannot be undone and account will be permanently removed.' }}
    </p>
  </PopupDialog>
</template>
