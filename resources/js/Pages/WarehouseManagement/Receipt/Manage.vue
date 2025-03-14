<script setup>
import { reactive, ref, watch, onMounted } from "vue";
import { mdiViewList, mdiAccount, mdiMail, mdiKey, mdiFileDocumentPlus } from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import GroupTitle from "@/Components/GroupTitle.vue";
import _ from "lodash";
import axios from "axios";
import service from "@/Components/Toast/service";
import {  ORDERSTATUS } from "@/constants";
import { Head, router } from '@inertiajs/vue3'
import Tab from "@/Components/Tab.vue";


const grpMenus = ref([]);
const props = defineProps({ action: String, formdata: Object });

grpMenus.value = _.groupBy(props.menus, "group");

let toast = service();
let selectOrderStatusOptions = ORDERSTATUS;
const formheader = reactive({
  id: "",
  receiptno: "",
  receiptdate: "",
  receipttype: "",
  referenceno: "",
  supplier_id:"",
  remarks: "",
  status: selectOrderStatusOptions[0],
});

const emit = defineEmits(["triggerTopRightButton"]);

const submit = () => {
  let data = { ...formheader };
  data.status = formheader.status.id
  data.receiptno = formheader.receiptno
  data.receiptdate = formheader.receiptdate
  data.receipttype = formheader.receipttype
  data.referenceno = formheader.referenceno
  data.supplier_id = formheader.supplier_id
  data.remarks = formheader.remarks
  axios.post('/im/receipt/store', data)
    .then(resp => {
      toast.success(`Receipt successfully saved!`);
      router.visit('/im/receive-shipment')
    })
    .catch(err => {
      alert(err.message);
      console.log(err);
    })
}

let activetab = ref('formheader')

watch(() => props.formdata, async (first, second) => {
  if (first != second && first) {
    formheader.id = first.id;
    formheader.receiptno = first.receiptno;
    formheader.receiptdate = first.receiptdate;
    formheader.receipttype = first.receipttype;
    formheader.referenceno = first.referenceno;
    formheader.supplier_id = first.supplier_id;
    formheader.remarks = first.remarks;
    formheader.status = selectOrderStatusOptions.find((status) => status.id == first.status);
  }
});
</script>

<template>

  <Head title="Add Shipments" />
  <SectionTitleLineWithButton :icon="mdiFileDocumentPlus" :title="props.action + ' Shipments'" main>
    <BaseButton @click="emit('triggerTopRightButton', 'listview')" :icon="mdiViewList" label="Receipt Lists"
      color="contrast" rounded-full small />
  </SectionTitleLineWithButton>
  <div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
      <li class="mr-2">
        <Tab label="Receipt Form" :icon="mdiAccount" name="formheader" :activeTab="activetab" @click="activetab = 'formheader'" />
      </li>
      <li class="mr-2">
        <Tab label="Received Items" :icon="mdiAccount" name="formitems" :activeTab="activetab" @click="activetab = 'formitems'" />
      </li>
    </ul>
  </div>
  <CardBox is-form @submit.prevent="submit">
    <div v-show="activetab === 'formheader'">
      <FormField label="Receipt No" >
        <FormControl v-model="formheader.receiptno" required />
        <div v-if="$page.props.errors.receiptno && !formheader.receiptno"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.receiptno }}</div>
      </FormField>

      <FormField label="Reference No" >
        <FormControl v-model="formheader.referenceno" required />
        <div v-if="$page.props.errors.referenceno && !formheader.referenceno"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.referenceno }}</div>
      </FormField>
      <FormField label="Receipt Date" help="Required">
        <FormControl v-model="formheader.receiptdate" :type="'date'" required />
        <div v-if="$page.props.errors.receiptdate && !formheader.receiptdate"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.receiptdate }}</div>
      </FormField>

      <FormField label="Supplier">
        <FormControl v-model="formheader.supplier_id" required  />
        <div v-if="$page.props.errors.supplier_id && !formheader.supplier_id"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.supplier_id }}</div>
      </FormField>
      <FormField label="Status">
        <FormControl v-model="formheader.status" :options="selectOrderStatusOptions" />
        <div v-if="$page.props.errors.status"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.status }}</div>
      </FormField>
      <BaseDivider />
      <FormField label="Remarks">
        <FormControl v-model="formheader.remarks" :type="'textarea'" />
        <div v-if="$page.props.errors.remarks && !formheader.remarks"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.remarks }}</div>
      </FormField>
      
    </div>

    <template #footer>
      <BaseButtons>
        <BaseButton type="submit" color="info" label="Save" :class="{ 'opacity-25': formheader.processing }"
          :disabled="formheader.processing" />
        <BaseButton v-if="action === 'Add'" id="reset" type="reset" color="info" outline label="Clear" />
      </BaseButtons>
    </template>
  </CardBox>
</template>

<style lang="scss" scoped></style>
