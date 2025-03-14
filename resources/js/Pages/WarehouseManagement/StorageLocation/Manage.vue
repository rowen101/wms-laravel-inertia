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
import axios from "axios";
import service from "@/Components/Toast/service";
import {AISTATUS} from "@/constants";
import { Head,router,useForm,usePage } from '@inertiajs/vue3'
import Tab from "@/Components/Tab.vue";
import ErrorAlert from "@/Components/Common/ErrorAlert.vue";

const page = usePage();
 

const props = defineProps({ action: String,data:Object, formdata: Object, errors: Object,storageclass:Array });

const formErrors = ref([]);
const storageClass =ref('');
let toast = service();
 
let selectStatusOptions = AISTATUS; 


let form = useForm({
  _token: page.props.csrf_token,
  id:props.data?.id ? props.data.id : null,
  storageclass_id : storageClass.value ? storageClass.value.id : null,
  loccode: props.data?.loccode ? props.data.loccode : null,
  tracecode: props.data?.tracecode ? props.data.tracecode : null,
  locationtype: props.data?.locationtype ? props.data.locationtype : null,
  sizecode: props.data?.sizecode ? props.data.sizecode : null,
  abccode: props.data?.abccode ? props.data.abccode : null,
  checkdigit: props.data?.checkdigit ? props.data.checkdigit : null,
  capacity: props.data?.capacity ? props.data.capacity : 1,
  drivezone: props.data?.drivezone ? props.data.drivezone : null,
  pickzone: props.data?.pickzone ? props.data.pickzone : null,
  picksequence: props.data?.picksequence ? props.data.picksequence : null,
  putawaysequence: props.data?.putawaysequence ? props.data.putawaysequence : null,
  islockin: props.data?.islockin ? props.data.islockin : null,
  islockout: props.data?.islockout ? props.data.islockout : null,
  isoverflow: props.data?.isoverflow ? props.data.isoverflow : null,
  status: props.data?.status ? selectStatusOptions.find(status => status.id === props.data?.status) : selectStatusOptions[0],
});


const emit = defineEmits(["triggerTopRightButton"]);

const submit = () => {
  form.storageclass_id = storageClass.value.id;
  form.post('/wm/locations/store', {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(`Location successfully saved!`);
      router.visit('/wm/locations')
    },
    onError: (errors) => {
      formErrors.value =  Object.values(errors || {});
    },
  })
}

let activetab = ref('profile')

onMounted(() => {});

watch(() => props.formdata, (newvalue, oldvalue) => {
    if (newvalue != oldvalue && newvalue) {
      for (let key in newvalue) {
        if (newvalue.hasOwnProperty(key)) {
          form[key] = newvalue[key];
        }
      }
      form.status = selectStatusOptions.find(status => status.id === newvalue.status)
     storageClass.value = props.storageclass.find(storageclass => storageclass.id === newvalue.storageclass_id)
    } 
});
</script>

<template>
  <ErrorAlert v-show="formErrors.length > 0" :formErrors="formErrors" />
  <Head title="Add Location" />
    <SectionTitleLineWithButton :icon="mdiSeed" :title="props.action + ' Location'" main>
      <BaseButton
          @click="emit('triggerTopRightButton','lists')"
          :icon="mdiViewList "
          label="Location Master Lists"
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
                :icon="mdiSeed"
                name="profile"
                :activeTab="activetab"
                @click="activetab = 'profile'"
                />
          </li>
          <li class="mr-2">
            <Tab
              label="Storage Handling"
              name="storagehandling"
              :activeTab="activetab"
              @click="activetab = 'storagehandling'"
              />
        </li>
       
      </ul>
    </div>
    <CardBox is-form @submit.prevent="submit">
      <div v-show="activetab === 'profile'"> 
        <FormField label="Storage Class">
          <FormControl v-model="storageClass" :options="props.storageclass" />
          <div v-if="$page.props.errors.storageclass_id" class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">{{$page.props.errors.status}}</div>
        </FormField>

        <FormField label="Location Code" help="Required. Location Code">
          <FormControl name="loccode" v-model="form.loccode" :icon="mdiSeed"  />
        </FormField>
        

        <FormField label="Tracecode">
          <FormControl name="tracecode" v-model="form.tracecode" :icon="mdiSeed"  />
          
        </FormField>
        
        <FormField label="Locationtype" help="Required. Locationtype">
          <FormControl name="locationtype" v-model="form.locationtype"  :icon="mdiMail" />
        </FormField>
        <FormField label="Status">
          <FormControl v-model="form.status" :options="selectStatusOptions" />
          <div v-if="$page.props.errors.status" class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">{{$page.props.errors.status}}</div>
        </FormField>

        
      
      </div>
      <div v-show="activetab === 'storagehandling'"> 
        <div class="flex flex-col space-y-4 ">
          <FormField label="Sizecode">
            <FormControl name="sizecode" v-model="form.sizecode"  />
          </FormField>
          <FormField label="Abccode">
            <FormControl name="abccode" v-model="form.abccode"  />
          </FormField>
          <FormField label="Checkdigit">
            <FormControl name="checkdigit" v-model="form.checkdigit" />
          </FormField>        
          <FormField label="Capacity">
            <FormControl name="capacity" v-model="form.capacity" type="number"  />
          
          </FormField>
          <FormField label="Drivezone">
            <FormControl name="drivezone" v-model="form.drivezone" />
          </FormField>
          <FormField label="Pickzone">
            <FormControl name="pickzone" v-model="form.pickzone" />
          </FormField>
          <FormField label="Picksequence">
            <FormControl name="picksequence" v-model="form.picksequence" />
          </FormField>
          <FormField label="Putaway sequence">
            <FormControl name="putawaysequence" v-model="form.putawaysequence" />
          </FormField>

          <FormField label="Is lockin">
            <FormCheckRadioGroup
              v-model="form.islockin"
              name="islockin"
              type="switch"
              :options="{ 1: 'Yes'}"
            />
          </FormField>
          <FormField label="Is lockout">
            <FormCheckRadioGroup
              v-model="form.islockout"
              name="islockout"
              type="switch"
              :options="{ 1: 'Yes'}"
            />
          </FormField>
          <FormField label="Is overflow">
            <FormCheckRadioGroup
              v-model="form.isoverflow"
              name="isoverflow"
              type="switch"
              :options="{ 1: 'Yes'}"
            />
          </FormField>
        
        </div>
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
