<script setup>
import { reactive, ref, watch, onMounted, computed, inject } from "vue";
import { mdiViewList, mdiAccount, mdiMail, mdiKey, mdiAccountPlus, mdiTrashCan } from "@mdi/js";
import CardBox from "@/Components/CardBox.vue";
import FormField from "@/Components/FormField.vue";
import FormControl from "@/Components/FormControl.vue";
import BaseButton from "@/Components/BaseButton.vue";
import BaseButtons from "@/Components/BaseButtons.vue";
import SectionTitleLineWithButton from "@/Components/SectionTitleLineWithButton.vue";
import _ from "lodash";
import CardBoxModal from "@/Components/CardBoxModal.vue";
import axios from "axios";
import service from "@/Components/Toast/service";
import { WAREHOUSESTATUS } from "@/constants";
import { Head, router, useForm } from '@inertiajs/vue3'
import Tab from "@/Components/Tab.vue";
import ErrorAlert from "@/Components/Common/ErrorAlert.vue";
import CoreTable from "@/Components/CoreTable.vue";
import CardBoxComponentEmpty from "@/Components/CardBoxComponentEmpty.vue";
import LookUpModal from "@/Components/LookUpModal.vue";
import PopupDialog from "@/Components/PopupDialog.vue";

let selectWarehouseStatusOptions = WAREHOUSESTATUS;
const props = defineProps({ action: String, formdata: Object, errors: Object });
const openUserModal = ref(false);
let activetab = ref('warehouse')
const tableUserRows = ref([]);
const availableUser = ref([]);
const storageClass = inject('storageClass');
const userHeader = ref([
  { label: 'Action', fieldName: 'id', type: 'slot' },
  { label: 'User', fieldName: 'name' },
  { label: 'Email', fieldName: 'email' },
  { label: 'User Type', fieldName: 'user_type' },
]);
const formErrors = ref([])
const userToDelete = ref({});
let toast = service();
const showDeletePop = ref(false);
const wrStatus = ref('A');
const stcls =ref('');
const form = useForm({
  id: "",
  warehouse_code: "",
  warehouse_name: "",
  storageclass_id:'',
  status: selectWarehouseStatusOptions[0],
  warehouseUser: []
});

const emit = defineEmits(["triggerTopRightButton"]);

const submit = () => {

  form.users = tableUserRows.value;
  form.status = wrStatus.value.id;
  form.storageclass_id = stcls.value.id;
  form.post('/wm/warehouses/store', {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(`Customer successfully saved!`);
      router.visit('/wm/warehouses')
    },
    onError: (errors) => {
      formErrors.value = Object.values(errors || {});
    },
  })
}

const onAddUser = (u) => {
  form.warehouseUser.push({
    id: u.id,
    name: u.name,
    email: u.email,
    user_type: u.user_type
  });

}

const onAvailableUser = async () => {

  let warehouseId = form.id == "" ? 0 : form.id;
  await axios.get(`/wm/warehouse-users/${warehouseId}`)
    .then((response) => {
      availableUser.value =  response.data;
      availableUser.value = response.data.filter((user) => {
        return !form.warehouseUser.some((row) => row.id === user.id);
      });

      openUserModal.value = true;

    })
    .catch((error) => {
      console.log(error);
    });
}

watch(() => props.formdata, async (first, second) => {
  if (first != second && first) {
    form.id = first.id
    form.warehouse_code = first.warehouse_code
    form.warehouse_name = first.warehouse_name
    wrStatus.value = selectWarehouseStatusOptions.find(status => status.id === first.status)
    stcls.value = storageClass.find(storageclass => storageclass.id === first.storageclass_id)
    form.warehouseUser =  first.warehouseUser

  }
});

const confirmLookup = (selected) => {
  selected.forEach((user) => {
    onAddUser(user);
  });
  openUserModal.value = false;
}

const onRemoveUser = (linkValue) => {
  userToDelete.value = linkValue
  showDeletePop.value = true
}

const popAction =async (action) => {
  showDeletePop.value = false
  if (action === 'ok') {
    await onDeleteWarehouseUser()
    form.warehouseUser = form.warehouseUser.filter((user) => user.id !== userToDelete.value.id);
  }
}

const onDeleteWarehouseUser = async() => {
  axios.delete(`/wm/warehouse-users/${form.id}/${userToDelete.value.id}`)
    .then(response => {
      toast.success(`Warehouse user record successfully deleted!`);
    }).catch(err => {
      console.log(err)
    });
};



</script>

<template>

  <Head :title="props.action + ' Warehouse'" />
  <LookUpModal v-model="openUserModal" title="User List" width="w-4/5" 
  @confirm="confirmLookup"
  :records="availableUser" :table-header="userHeader" searchable-fields="id,name,email,user_type" />
  <SectionTitleLineWithButton :icon="mdiAccountPlus" :title="props.action + ' Warehouse'" main>
    <BaseButton @click="emit('triggerTopRightButton', 'lists')" :icon="mdiViewList" label="Warehouse Lists"
      color="contrast" rounded-full small />
  </SectionTitleLineWithButton>

  <ErrorAlert :formErrors="formErrors" />
  <div class="border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
      <li class="mr-2">
        <Tab label="Warehouse" :icon="mdiAccount" name="warehouse" :activeTab="activetab"
          @click="activetab = 'warehouse'" />
        <Tab label="Users" :icon="mdiAccount" name="users" :activeTab="activetab" @click="activetab = 'users'" />
      </li>
    </ul>
  </div>
  <CardBox is-form @submit.prevent="submit">
    <div v-show="activetab === 'warehouse'">
      <FormField label="Code">
        <FormControl v-model="form.warehouse_code" :icon="mdiAccount" />
      </FormField>

      <FormField label="Name">
        <FormControl v-model="form.warehouse_name" :icon="mdiMail" />
      </FormField>

      <FormField label="Storage Class">
        <FormControl v-model="stcls" :options="storageClass" />
        <div v-if="$page.props.errors.storageclass_id"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.storageclass_id }}</div>
      </FormField>

      <FormField label="Status">
        <FormControl v-model="wrStatus" :options="selectWarehouseStatusOptions" />
        <div v-if="$page.props.errors.status"
          class="bg-red-500 border-red-500 text-white px-3 py-6 md:py-3 mb-6 last:mb-0 border rounded-lg transition-colors duration-150">
          {{ $page.props.errors.status }}</div>
      </FormField>

    </div>

    <div v-show="activetab === 'users'">
      <SectionTitleLineWithButton :icon="mdiAccountGroup" title="User list" main>
    <BaseButton @click="onAvailableUser" :icon="mdiFileDocumentPlus" label="Add User" color="contrast"
      rounded-full small />
  </SectionTitleLineWithButton>

      <CardBox class="flex-1 p-6" has-table>
        <CoreTable :table-rows="form.warehouseUser" :table-header="userHeader"
          table-name="warehouseUser" searchable-fields="id,name,email,user_type" :is-paginated="true"
          @openLink="openLink" >
          <template #row-action="{ slotProp }">
              <BaseButton small :icon="mdiTrashCan" label="" color="danger" @click="onRemoveUser(slotProp)" />
            </template>
        </CoreTable>

      </CardBox>
    </div>

    <template #footer>
      <BaseButtons>
        <BaseButton type="submit" color="info" label="Save" :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing" />
        <BaseButton v-if="action === 'Add'" id="reset" type="reset" color="info" outline label="Clear" />
      </BaseButtons>
    </template>
  </CardBox>


    <!-- Popup Dialog Confirmation-->
    <PopupDialog :open="showDeletePop" popupTitle="Delete Confirmation" okButtonText="Yes, Delete" cancelButtonText="Cancel"
    :showCancelButton="true" :icon=mdiDeleteAlert theme="danger" @popaction="popAction">
    <p class="text-sm text-gray-500">{{ 'Are you sure you want to delete warehouse user  ' + (userToDelete.id + ', ' +
      userToDelete.name).toLocaleUpperCase() + ' ? This action cannot be undone and account will be permanently removed.' }}</p>
  </PopupDialog>
</template>

<style lang="scss" scoped></style>
