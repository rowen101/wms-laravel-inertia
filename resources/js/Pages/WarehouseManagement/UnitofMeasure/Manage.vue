<script setup>
import { reactive, ref,watch,computed } from "vue";
import { mdiViewList, mdiAccount, mdiMail,  mdiKey, mdiAccountPlus } from "@mdi/js";
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

import { Head,router,useForm,usePage } from '@inertiajs/vue3'
import Tab from "@/Components/Tab.vue";

const page = usePage();
const grpMenus = ref([]);
const props = defineProps({ action: String,data:Object, formdata: Object, errors: Object });

const formErrors = computed(() => {
  return Object.values(props.errors || {});
});

grpMenus.value = _.groupBy(props.menus, "group");
let toast = service();

const form = useForm({
 _token: page.props.csrf_token,
  id: props.data?.id ? props.data.id : null,
  uomcode: props.data?.uomcode ?props.data.uomcode : null,
  description:props.data?.description ?props.data.description : null,
 
});

const emit = defineEmits(["triggerTopRightButton"]);

const submit =() =>{ 
  let data = {...form};
  data.uomcode = form.uomcode
  data.description = form.description
   axios.post('/wm/uom/store', data)
   .then(resp => {
      toast.success(`Unit of Measure successfully saved!`);
      router.visit('/wm/uom')
   })
   .catch(err =>{
      console.log(err);
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
 
  }
});
</script>

<template>
  <Head title="Add Unit of Measure" />
    <SectionTitleLineWithButton :icon="mdiAccountPlus" :title="props.action + ' Unit Of Measure'" main>
      <BaseButton
          @click="emit('triggerTopRightButton','lists')"
          :icon="mdiViewList "
          label="Unit of Measure Lists"
          color="contrast"
          rounded-full
          small
        />
    </SectionTitleLineWithButton>
    <ErrorAlert :formErrors="formErrors" />

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
        <FormField label="UOM Code" help="Required.  UOM Code">
          <FormControl v-model="form.uomcode" required />
          <div v-if="$page.props.errors.uomcode && !form.uomcode" class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">{{$page.props.errors.code}}</div>
        </FormField>
        
        <FormField label="Description" help="Required. Description">
          <FormControl v-model="form.description"    required/>
          <div v-if="$page.props.errors.description && !form.description" class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">{{$page.props.errors.name}}</div>
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
