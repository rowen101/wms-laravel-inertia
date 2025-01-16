<template>
  <div>
    <!-- Dashboard Menu -->
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
        <icon name="dashboard" class="mr-2 w-4 h-4" :class="isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>

    <!-- Organizations Menu -->
    <div class="mb-4">
      <div class="flex items-center justify-between">
        <div @click="toggleSubmenu('organizations')" class="group flex items-center cursor-pointer py-3">
          <icon name="office" class="mr-2 w-4 h-4" :class="submenuVisible === 'organizations' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
          <div :class="submenuVisible === 'organizations' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Organizations</div>
        </div>
        <icon
          name="chevron-down"
          class="cursor-pointer transform transition-transform duration-300"
          :class="submenuVisible === 'organizations' ? 'rotate-180' : ''"
          @click="toggleSubmenu('organizations')"
        />
      </div>
      <!-- Submenu -->
      <div v-show="submenuVisible === 'organizations'" class="ml-6 mt-2">
        <Link class="block py-2 text-indigo-300 group-hover:text-white" href="/organizations/create">Create Organization</Link>
        <Link class="block py-2 text-indigo-300 group-hover:text-white" href="/organizations/manage">Manage Organizations</Link>
      </div>
    </div>

    <!-- Contacts Menu -->
    <div class="mb-4">
      <div class="flex items-center justify-between">
        <div @click="toggleSubmenu('contacts')" class="group flex items-center cursor-pointer py-3">
          <icon name="users" class="mr-2 w-4 h-4" :class="submenuVisible === 'contacts' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
          <div :class="submenuVisible === 'contacts' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Contacts</div>
        </div>
        <icon
          name="chevron-down"
          class="cursor-pointer transform transition-transform duration-300"
          :class="submenuVisible === 'contacts' ? 'rotate-180' : ''"
          @click="toggleSubmenu('contacts')"
        />
      </div>
      <!-- Submenu -->
      <div v-show="submenuVisible === 'contacts'" class="ml-6 mt-2">
        <Link class="block py-2 text-indigo-300 group-hover:text-white" href="/contacts/add">Add Contact</Link>
        <Link class="block py-2 text-indigo-300 group-hover:text-white" href="/contacts/import">Import Contacts</Link>
      </div>
    </div>

    <!-- Reports Menu -->
    <div class="mb-4">
      <div class="flex items-center justify-between">
        <div @click="toggleSubmenu('reports')" class="group flex items-center cursor-pointer py-3">
          <icon name="printer" class="mr-2 w-4 h-4" :class="submenuVisible === 'reports' ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
          <div :class="submenuVisible === 'reports' ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Reports</div>
        </div>
        <icon
          name="chevron-down"
          class="cursor-pointer transform transition-transform duration-300"
          :class="submenuVisible === 'reports' ? 'rotate-180' : ''"
          @click="toggleSubmenu('reports')"
        />
      </div>
      <!-- Submenu -->
      <div v-show="submenuVisible === 'reports'" class="ml-6 mt-2">
        <Link class="block py-2 text-indigo-300 group-hover:text-white" href="/reports/daily">Daily Reports</Link>
        <Link class="block py-2 text-indigo-300 group-hover:text-white" href="/reports/monthly">Monthly Reports</Link>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'

export default {
  components: {
    Icon,
    Link,
  },
  data() {
    return {
      submenuVisible: null, // Tracks the currently open submenu
    }
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
    toggleSubmenu(menu) {
      // Toggle the visibility of the submenu
      this.submenuVisible = this.submenuVisible === menu ? null : menu
    },
  },
}
</script>
