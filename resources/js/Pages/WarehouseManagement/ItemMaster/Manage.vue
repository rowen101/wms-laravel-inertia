<script setup>
import { reactive, ref, watch, computed } from "vue";
import { mdiViewList, mdiAccount, mdiMail, mdiKey, mdiSeed } from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import FormCheckRadioGroup from "@/Components/FormCheckRadioGroup.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import GroupTitle from "@/Components/GroupTitle.vue";
import _ from "lodash";
import axios from "axios";
import service from "@/Components/Toast/service";
import { AISTATUS } from "@/constants";
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import Tab from "@/Components/Tab.vue";
import ErrorAlert from "@/Components/Common/ErrorAlert.vue";

const page = usePage();
const grpMenus = ref([]);
const props = defineProps({ action: String,data:Object, formdata: Object, errors: Object });

const formErrors = ref([]);

grpMenus.value = _.groupBy(props.menus, "group");

let toast = service();

let selectStatusOptions = AISTATUS;

let form = useForm({
  _token: page.props.csrf_token,
  id: props.data?.id ? props.data.id : null,
  storageclass_id: props.data?.storageclass_id ?props.data.storageclass_id : null,
  producttype: props.data?.producttype ?props.data.producttype : null,
  itemcode: props.data?.itemcode ? props.data.itemcode : null,
  referencecode: props.data?.referencecode ? props.data.referencecode : null,
  description: props.data?.description ? props.data.description : null,
  movementclass:props.data?.movementclass ?props.data.movementclass :  null,
  unitprice:props.data?.unitprice ? props.data.unitprice :  null,
  shelflife:props.data?.shelflife ?  props.data.shelflife : null,
  shelflifeunit:props.data?.shelflifeunit? props.data.shelflifeunit : 'D',
  salvagedays:props.data?.salvagedays ? props.data.salvagedays :  null,
  stockrestriction: props.data?.stockrestriction ? props.data.stockrestriction : 'M',
  lotformat: props.data?.lotformat ? props.data.lotformat : null,
  lotformatdate: props.data?.lotformatdate ? props.data.lotformatdate : 'E',
  batchfindstrategy: props.data?.batchfindstrategy ?props.data.batchfindstrategy : 'FEFO',
  safestocklevel: props.data?.safestocklevel ? props.data.safestocklevel : null,
  handlinguom: props.data?.handlinguom ? props.data.handlinguom : null,
  eachuom: props.data?.eachuom ? props.data.eachuom : null,
  eachtohandlingqty: props.data?.eachtohandlingqty ? props.data.eachtohandlingqty : null,
  handlingtopalletqty:props.data?.handlingtopalletqty ? props.data.handlingtopalletqty : null,
  minlevelhandlingqty:  props.data?.minlevelhandlingqty ? props.data.minlevelhandlingqty : null,
  maxreplineshmentforcaseloc: props.data?.maxreplineshmentforcaseloc ? props.data.maxreplineshmentforcaseloc : null,
  handlinglocation: props.data?.handlinglocation  ? props.data.handlinglocation : null,
  eachlocation: props.data?.eachlocation ?props.data.eachlocation : null,
  isbatchmanaged:  props.data?.isbatchmanaged ? props.data.isbatchmanaged : 1,
  extraproperties: props.data?.extraproperties? props.data.extraproperties : null,
  status: selectStatusOptions[0],

});

const emit = defineEmits(["triggerTopRightButton"]);

const submit = () => {
  let data = { ...form };
  form.post('/wm/item-master/store', {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(`Item successfully saved!`);
      router.visit('/wm/item-master')
    },
    onError: (errors) => {

      formErrors.value =  Object.values(errors || {});
    },
  })

}

let activetab = ref('profile')

watch(() => props.formdata, (newvalue, oldvalue) => {
  if (newvalue != oldvalue && newvalue) {
    for (let key in newvalue) {
      if (newvalue.hasOwnProperty(key)) {
        form[key] = newvalue[key];
      }
    }
    form.status = selectStatusOptions.find(status => status.id === newvalue.status)
  }
});
</script>

<template>
  <ErrorAlert v-show="formErrors.length > 0" :formErrors="formErrors" />
  <Head title="Add Item" />
  <SectionTitleLineWithButton :icon="mdiSeed" :title="props.action + ' Item'" main>
    <BaseButton @click="emit('triggerTopRightButton', 'lists')" :icon="mdiViewList" label="Item Master Lists"
      color="contrast" rounded-full small />
  </SectionTitleLineWithButton>
  <div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
      <li class="mr-2">
        <Tab label="Profile" :icon="mdiSeed" name="profile" :activeTab="activetab" @click="activetab = 'profile'" />
      </li>
      <li v-show="form.isbatchmanaged" class="mr-2">
        <Tab label="Batch Management" name="batchmanagement" :activeTab="activetab"
          @click="activetab = 'batchmanagement'" />
      </li>
      <li class="mr-2">
        <Tab label="Others" name="others" :activeTab="activetab" @click="activetab = 'others'" />
      </li>
    </ul>
  </div>
  <CardBox is-form @submit.prevent="submit">
    <div v-show="activetab === 'profile'">
      <FormField label="Item Code" help="Required. Item Code">
        <FormControl name="itemcode" v-model="form.itemcode" :icon="mdiSeed" />

      </FormField>

      <FormField label="Storage Class" help="Required. Storage Class">
        <FormControl name="storageclass" v-model="form.storageclass_id" :icon="mdiSeed" />

      </FormField>

      <FormField label="Description" help="Required. Item Description">
        <FormControl name="description" v-model="form.description" :icon="mdiMail" />

      </FormField>
      <FormField label="Unit Price">
        <FormControl name="unitprice" v-model="form.unitprice" />
      </FormField>
      <FormField label="Product Type">
        <FormControl name="producttype" v-model="form.producttype" />
      </FormField>
      <FormField label="Reference Code">
        <FormControl name="referencecode" v-model="form.referencecode" />
      </FormField>
      <FormField label="Batch Managed ?">
        <FormCheckRadioGroup v-model="form.isbatchmanaged" name="isbatchmanaged" type="switch"
          :options="{ 1: 'Yes' }" />
      </FormField>
      <FormField label="Status">
        <FormControl name="status" v-model="form.status" :options="selectStatusOptions" />
        <div v-if="$page.props.errors.status"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.status }}</div>
      </FormField>
    </div>
    <div v-show="activetab === 'batchmanagement' && form.isbatchmanaged">
      <FormField label="Movement Class">
        <FormControl name="movementclass" v-model="form.movementclass" />
      </FormField>
      <FormField label="Shelf Life">
        <FormControl name="shelflife" v-model="form.shelflife" type="number" />
      </FormField>
      <FormField label="Shelf Life Unit">
        <FormCheckRadioGroup v-model="form.shelflifeunit" name="shelflifeunit" type="radio"
          :options="{ D: 'Days', M: 'Months' }" />
      </FormField>
      <FormField label="Salvage Days">
        <FormControl name="salvagedays" v-model="form.salvagedays" />
      </FormField>
      <FormField label="Lot Format">
        <FormControl name="lotformat" v-model="form.lotformat" placeholder="i.e MMDDYYYY" />
      </FormField>
      <FormField label="Lot Format Represents">
        <FormCheckRadioGroup v-model="form.lotformatdate" name="lotformatdate" type="radio"
          :options="{ E: 'Expiry Date', M: 'Manufacturing Date' }" />
      </FormField>
      <FormField label="Safe Stock Level">
        <FormControl name="safestocklevel" v-model="form.safestocklevel" />
      </FormField>
      <FormField label="Handling Unit">
        <FormControl name="handlinguom" v-model="form.handlinguom" />
      </FormField>
      <FormField label="Handling Qty per Pallet">
        <FormControl name="handlingtopalletqty" v-model="form.handlingtopalletqty" />
      </FormField>
      <FormField label="Eaches Unit">
        <FormControl name="eachuom" v-model="form.eachuom" />
      </FormField>
      <FormField label="Eaches Qty per Handling Unit">
        <FormControl name="eachtohandlingqty" v-model="form.eachtohandlingqty" />
      </FormField>
      <FormField label="Minimum Handling Unit Qty to Replenish">
        <FormControl name="minlevelhandlingqty" v-model="form.minlevelhandlingqty" />
      </FormField>
      <FormField label="Maximum Handling Unit Qty to Replenish">
        <FormControl name="maxreplineshmentforcaseloc" v-model="form.maxreplineshmentforcaseloc" />
      </FormField>
      <FormField label="Handling Unit Picking Location">
        <FormControl name="handlinglocation" v-model="form.handlinglocation" />
      </FormField>
      <FormField label="Eaches Unit Picking Location">
        <FormControl name="eachlocation" v-model="form.eachlocation" />
      </FormField>
      <FormField label="Location Stocking Restrictions">
        <FormCheckRadioGroup v-model="form.stockrestriction" name="stockrestriction" type="radio"
          :options="{ M: 'by Expiry Month', D: 'by Expirate Date', I: 'by Item' }" />
      </FormField>
      <FormField label="Batch Finding Strategy">
        <FormCheckRadioGroup v-model="form.batchfindstrategy" name="batchfindstrategy" type="radio"
          :options="{ FEFO: 'First Expiry First Out', FIFO: 'First In First Out' }" />
      </FormField>
    </div>
    <div v-show="activetab === 'others'">
      <FormField label="Extra Properties">
        <FormControl name="extraproperties" v-model="form.extraproperties" type="textarea"
          placeholder="Extra Properties" />
      </FormField>
    </div>
    <BaseDivider />
    <template #footer>
      <BaseButtons>
        <BaseButton type="submit" color="info" label="Save" :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing" />
        <BaseButton v-if="action === 'Add'" id="reset" type="reset" color="info" outline label="Clear" />
      </BaseButtons>
    </template>
  </CardBox>
</template>

<style lang="scss" scoped></style>
