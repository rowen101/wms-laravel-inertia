<template>
  <div>
    <!-- Used to animate the entire thing -->
    <TransitionRoot appear :show="isOpen" as="div">
      <!-- Parent dialog  -->
      <Dialog as="div" @close="isOpen = false" class="fixed inset-0 z-50 flex justify-start">
        <!-- Transition for overlay/backdrop -->
        <TransitionChild
          as="div"
          enter="duration-300 ease-out"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="duration-200 ease-in"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <!-- The actual backdrop -->
          <div class="fixed inset-0 bg-black/25 backdrop-blur" />
        </TransitionChild>

        <TransitionChild
          as="div"
          enter="duration-300 ease-out"
          enter-from="opacity-0 -translate-x-full"
          leave="duration-200 ease-in"
          leave-to="opacity-0 -translate-x-full"
        >
          <DialogPanel class="relative h-dvh w-[320px] p-3">
            <div class="absolute -right-10 top-4 z-20">
              <button
                @click="isOpen = false"
                aria-label="Close menu"
                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border bg-background"
              >
                <span class="sr-only">Button used to close menu</span>
                <Icon name="heroicons:x-mark" />
              </button>
            </div>
            <Sidebar class="sticky top-0 h-full w-full max-w-sm" />
          </DialogPanel>
        </TransitionChild>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<script setup>
 import { TransitionRoot, TransitionChild, Dialog, DialogPanel } from '@headlessui/vue'
import Sidebar from "./Sidebar.vue"
const isOpen = defineModel();
</script>
