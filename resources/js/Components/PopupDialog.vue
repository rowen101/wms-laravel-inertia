<template>
    <TransitionRoot as="template" :show="open">
      <Dialog as="div" class="relative z-10" @close="$emit('popaction','close')">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
          <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>
  
        <div class="fixed inset-0 z-10 overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
              <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                  <div class="sm:flex sm:items-start">
                    <div class="rounded-full block h-auto bg-gray-100 dark:bg-slate-800">
                      <IconRounded v-if="icon" :icon="icon" :color="themeclass.iconcolor" aria-hidden="true" />
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                      <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">{{popupTitle}}</DialogTitle>
                      <div class="mt-2">
                        <slot></slot>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                  <button type="button" class="inline-flex w-full justify-center rounded-md border border-transparent  px-4 py-2 text-base font-medium sm:ml-3 sm:w-auto sm:text-sm" :class="themeclass.okButton" @click="$emit('popaction','ok')">{{ okButtonText }}</button>
                  <button v-show="showCancelButton" type="button" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="$emit('popaction','close')" ref="cancelButtonRef">{{ cancelButtonText }}</button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </template>
  
  <script setup>
  import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
  import IconRounded from './IconRounded.vue';
  import {computed} from "vue";

  const props = defineProps({
    popupTitle: {
        type: String,
        required:true
    },
    okButtonText: {
        type: String,
        required:true
    },
    cancelButtonText: {
        type: String,
        required:true
    },
    showCancelButton:{
        type: Boolean,
        default:true,
    },
    open:{
        type:Boolean,
        default:false
    },
    icon: {
        type: String,
        default: null,
    },
    theme: {
        type: String,
        default: 'info',
    },
    
  })

  const themeclass = computed (() => {
    if (props.theme.toLowerCase() === 'danger') {
      return {
        'okButton' : ' bg-red-600 text-white shadow-sm hover:bg-red-700  ',
        'iconcolor' : 'danger'
      }
    }

    if (props.theme.toLowerCase() === 'success') {
      return {
        'okButton' : ' bg-emerald-600 text-white shadow-sm hover:bg-emerald-700  ',
        'iconcolor' : 'success'
      }
    }

    if (props.theme.toLowerCase() === 'warning') {
      return {
        'okButton' : ' bg-yellow-600 text-white shadow-sm hover:bg-yellow-700  ',
        'iconcolor' : 'warning'
      }
    }

    //default
    return {
      'okButton' : ' bg-blue-600 text-white shadow-sm hover:bg-blue-700  ',
      'iconcolor' : 'info'
    }

  })
  </script>
