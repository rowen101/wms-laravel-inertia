<script setup>
import { reactive, ref,watch ,onMounted} from "vue";
import { mdiViewList, mdiAccount, mdiMail,  mdiKey, mdiSeed } from "@mdi/js";
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
import {AISTATUS} from "@/constants";
import { Head,router,useForm,usePage } from '@inertiajs/vue3'
import Tab from "@/Components/Tab.vue";
import ErrorAlert from "@/Components/Common/ErrorAlert.vue";

const page = usePage();
const grpMenus = ref([]);

const props = defineProps({ action: String,data:Object, formdata: Object, errors: Object });
grpMenus.value = _.groupBy(props.menus, "group");


const formErrors = ref([]);

let toast = service();
 
let selectStatusOptions = AISTATUS; 
const selectWarehouseOptions = ref([]);

let form = useForm({
  _token: page.props.csrf_token,
  id:props.data?.id ? props.data.id : null,
  classcode: props.data?.classcode ? props.data.classcode : null,
  description: props.data?.description ? props.data.description : null,
});


const emit = defineEmits(["triggerTopRightButton"]);


const submit = () => {
  let data = { ...form };
  form.post('/wm/storage-class/store', {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(`Item successfully saved!`);
      router.visit('/wm/storage-class')
    },
    onError: (errors) => {

      formErrors.value =  Object.values(errors || {});
    },
  })
}

let activetab = ref('Activities')


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
  <Head title="Add Storage Class" />
    <SectionTitleLineWithButton :icon="mdiSeed" :title="props.action + ' Item'" main>
      <BaseButton
          @click="emit('triggerTopRightButton','lists')"
          :icon="mdiViewList "
          label="Storage Class Lists"
          color="contrast"
          rounded-full
          small
        />
    </SectionTitleLineWithButton>
    <div class="border-b border-gray-200 dark:border-gray-700">
      <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
          <li class="mr-2">
              <Tab
                label="Activities"
                :icon="mdiSeed"
                name="Activities"
                :activeTab="activetab"
                @click="activetab = 'Activities'"
                />
          </li>
      </ul>
    </div>
    <CardBox is-form @submit.prevent="submit">
      <div v-show="activetab === 'Activities'"> 
        <FormField label="Class Code" help="Required. Class Code">
          <FormControl name="classcode" v-model="form.classcode" :icon="mdiSeed"  />
        </FormField>
        

        <FormField label="Description" help="Required. Description">
          <FormControl name="description" v-model="form.description"  :icon="mdiMail" />
        </FormField>
        <FormField label="Is active">
  </FormField>
       
      
      </div>
     
      
      <BaseDivider />
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
