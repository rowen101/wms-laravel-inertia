<template>
    <aside>
      <div class="flex h-full w-full flex-col gap-5 overflow-y-auto rounded-md border bg-card pb-5 scrollbar-thin scrollbar-thumb-input scrollbar-thumb-rounded-md">
            <SidebarItem :links="page.props.menus || []" />
        <div class="mt-auto">
            
    
        </div>
      </div>
    </aside>

  </template>
  
  <script setup>
  import { usePage } from '@inertiajs/vue3';
  import { ref,onMounted  } from "vue";
  import SidebarItem from "./SidebarItem.vue";
  import { Icon } from "@iconify/vue";
  const page = usePage();

  onMounted(() =>{
   
  })
  </script>