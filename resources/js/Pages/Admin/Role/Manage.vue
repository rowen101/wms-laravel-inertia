<script setup>
import { reactive, ref, watch, onMounted,computed  } from "vue";
import { mdiViewList, mdiAccount, mdiMail, mdiKey, mdiAccountPlus } from "@mdi/js";
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
import { CUSTOMERCATEGORY, CUSTOMERSTATUS, ADDRESS } from "@/constants";
import province from "../../../Dataset/province.json";
import { Head, router,useForm } from '@inertiajs/vue3'
import Tab from "@/Components/Tab.vue";
import ErrorAlert from "@/Components/Common/ErrorAlert.vue";

const grpMenus = ref([]);
const props = defineProps({ action: String, formdata: Object, errors: Object  });


const formErrors = ref([])


grpMenus.value = _.groupBy(props.menus, "group");

let toast = service();

let selectCustomerCategoryOptions = CUSTOMERCATEGORY;
let selectCustomerStatusOptions = CUSTOMERSTATUS;
let customerAddress = ADDRESS;
let provinceOptions =ref(_.sortBy(_.map(province, (province) => {
  return {
    id: province.code,
    label: province.name
  };
}), 'label'));

let citiesOptions = ref([]);

const form = useForm({
  id: "",
  code: "",
  name: "",
  address: customerAddress,
  category: selectCustomerCategoryOptions[1],
  status: selectCustomerStatusOptions[0],
});

const emit = defineEmits(["triggerTopRightButton"]);

const submit = () => {
  let data = { ...form };
  data.status = form.status.id
  data.category = form.category.id
  data.address.province = form.address.province.label ?? ''
  data.address.city = form.address.city.label ?? ''

  form.post('/wm/customers/store', {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(`Customer successfully saved!`);
      router.visit('/wm/customers')
    },
    onError: (errors) => {
      formErrors.value =  Object.values(errors || {});
    },
  })
}

let activetab = ref('profile')

const getCity = async (province) => {
  const request1 = axios.get(`https://psgc.gitlab.io/api/provinces/${province.id}/cities/`);
    const request2 = axios.get(`https://psgc.gitlab.io/api/provinces/${province.id}/municipalities/`);

   await axios.all([request1, request2])
      .then(axios.spread((response1, response2) => {
        let arr1 = _.map(response1.data, (city) => {
          return {
            id: city.code,
            label: city.name
          };
        });

        let arr2 = _.map(response2.data, (city) => {
          return {
            id: city.code,
            label: city.name
          };
        });

        citiesOptions.value = _.sortBy(arr1.concat(arr2), 'label');
      }))
      .catch(error => {
        console.error('Error:', error);
      });
}

watch(() => props.formdata, async (first, second) => {
  if (first != second && first) {
    form.id = first.id
    form.code = first.code
    form.name = first.name
    form.address = first.address ?? customerAddress
    form.category = selectCustomerCategoryOptions.find(category => category.id === first.category)
    form.status = selectCustomerStatusOptions.find(status => status.id === first.status)
    form.address.province =  provinceOptions.value.find(lbl => lbl.label ==first.address.province  ) 

    await getCity(form.address.province);
    form.address.city = citiesOptions.value.find(lbl => lbl.label == first.address.city)
  }
});



const onSelectProvince = (e) => {
  form.address.city = null;
  getCity(form.address.province);
}


</script>

<template>

  <Head title="Add Customer" />
  <SectionTitleLineWithButton :icon="mdiAccountPlus" :title="props.action + ' Customer'" main>
    <BaseButton @click="emit('triggerTopRightButton', 'lists')" :icon="mdiViewList" label="Customer Lists"
      color="contrast" rounded-full small />
  </SectionTitleLineWithButton>

  <ErrorAlert :formErrors="formErrors" />
  <div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
      <li class="mr-2">
        <Tab label="Profile" :icon="mdiAccount" name="profile" :activeTab="activetab" @click="activetab = 'profile'" />
      </li>
    </ul>
  </div>
  <CardBox is-form @submit.prevent="submit">
    <div v-show="activetab === 'profile'">
      <FormField label="Code">
        <FormControl v-model="form.code" :icon="mdiAccount"  />
      </FormField>

      <FormField label="Name">
        <FormControl v-model="form.name" :icon="mdiMail"  />
      </FormField>

      <FormField label="Category">
        <FormControl v-model="form.category" :options="selectCustomerCategoryOptions" />
        <div v-if="$page.props.errors.category"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.category }}</div>
      </FormField>
      <FormField label="Status">
        <FormControl v-model="form.status" :options="selectCustomerStatusOptions" />
        <div v-if="$page.props.errors.status"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.status }}</div>
      </FormField>
      <BaseDivider />
      <GroupTitle class="mb-4">Customer Address</GroupTitle>
      <FormField :label="customerAddress.streetAddress.label">
        <FormControl v-model="form.address.streetAddress.value" />
      </FormField>
      <FormField :label="customerAddress.streetAddressL2.label">
        <FormControl v-model="form.address.streetAddressL2.value" />
      </FormField>
      <FormField :label="customerAddress.province.label">
        <FormControl v-model="form.address.province" :options="provinceOptions" @change="onSelectProvince" />
      </FormField>
      <FormField :label="customerAddress.city.label">
        <FormControl v-model="form.address.city" :options="citiesOptions" />
      </FormField>
      <FormField :label="customerAddress.postalCode.label">
        <FormControl v-model="form.address.postalCode.value" />
      </FormField>
    </div>

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
