<script setup>
import { reactive, ref,watch } from "vue";
import { mdiViewList, mdiAccount, mdiMail,  mdiKey, mdiAccountPlus } from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import BaseDivider from "@/Components/BaseDivider.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import GroupTitle from "@/Components/GroupTitle.vue";
import axios from "axios";
import service from "@/Components/Toast/service";
import {SUPPLIERCATEGORY,SUPPLIERSTATUS,ADDRESS} from "@/constants";
import { Head,router } from '@inertiajs/vue3'
import Tab from "@/Components/Tab.vue";
const props = defineProps({action:String,formdata:Object });
let toast = service();

let selectSupplierCategoryOptions = SUPPLIERCATEGORY;
let selectSupplierStatusOptions = SUPPLIERSTATUS;
let supplierAddress = ADDRESS;

const form = reactive({
  id: "",
  code: "",
  name: "",
  address:supplierAddress,
  category: selectSupplierCategoryOptions[1],
  status:selectSupplierStatusOptions[0],
});

const emit = defineEmits(["triggerTopRightButton"]);

const submit =() =>{ 
  let data = {...form};
  data.status = form.status.id
  data.category = form.category.id
   axios.post('/wm/suppliers/store', data)
   .then(resp => {
      toast.success(`Supplier successfully saved!`);
      router.visit('/wm/suppliers')
   })
   .catch(err =>{
      console.error(err);
   })
}

let activetab = ref('profile')

watch(() => props.formdata, (first, second) => {
    if (first != second && first) {
      form.id = first.id
      form.code=  first.code 
      form.name=  first.name 
      form.address = first.address ?? supplierAddress
      form.category = selectSupplierCategoryOptions.find(category => category.id === first.category),
      form.status = selectSupplierStatusOptions.find(status => status.id === first.status)
    } 
});
</script>

<template>
  <Head title="Add Supplier" />
    <SectionTitleLineWithButton :icon="mdiAccountPlus" :title="props.action + ' Supplier'" main>
      <BaseButton
          @click="emit('triggerTopRightButton','lists')"
          :icon="mdiViewList "
          label="Supplier Lists"
          color="contrast"
          rounded-full
          small
        />
    </SectionTitleLineWithButton>
    <div class="border-b border-gray-200 dark:border-gray-700">
      <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
          <li class="mr-2">
            <Tab
              label="Profile"
              :icon="mdiAccount"
              name="profile"
              :activeTab="activetab"
              @click="activetab = 'profile'"
            />
          </li>
      </ul>
    </div>
    <CardBox is-form @submit.prevent="submit">
      <div v-show="activetab === 'profile'">
        <FormField label="Code" help="Required. Supplier Code">
          <FormControl v-model="form.code" :icon="mdiAccount" required />
          <div v-if="$page.props.errors.code && !form.code" class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">{{$page.props.errors.code}}</div>
        </FormField>
        
        <FormField label="Name" help="Required. Supplier Name">
          <FormControl v-model="form.name"  :icon="mdiMail" required/>
          <div v-if="$page.props.errors.name && !form.name" class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">{{$page.props.errors.name}}</div>
        </FormField>
  
        <FormField label="Category">
          <FormControl v-model="form.category" :options="selectSupplierCategoryOptions" />
          <div v-if="$page.props.errors.category" class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">{{$page.props.errors.category}}</div>
        </FormField>
        <FormField label="Status">
          <FormControl v-model="form.status" :options="selectSupplierStatusOptions" />
          <div v-if="$page.props.errors.status" class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">{{$page.props.errors.status}}</div>
        </FormField>
        <BaseDivider />
        <GroupTitle class="mb-4">Supplier Address</GroupTitle>
        <FormField :label="supplierAddress.streetAddress.label">
          <FormControl v-model="form.address.streetAddress.value" />
        </FormField>
        <FormField :label="supplierAddress.streetAddressL2.label">
          <FormControl v-model="form.address.streetAddressL2.value" />
        </FormField>
        <FormField :label="supplierAddress.city.label">
          <FormControl v-model="form.address.city.value" />
        </FormField>
        <FormField :label="supplierAddress.province.label">
          <FormControl v-model="form.address.province.value" />
        </FormField>
        <FormField :label="supplierAddress.postalCode.label">
          <FormControl v-model="form.address.postalCode.value" />
        </FormField>
      </div>
      
      <template #footer>
        <BaseButtons>
          <BaseButton
            type="submit"
            color="info"
            label="Save"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          />
          <BaseButton v-if="action === 'Add'" id="reset" type="reset" color="info" outline label="Clear" />
        </BaseButtons>
      </template>
    </CardBox>
</template>

<style lang="scss" scoped></style>
